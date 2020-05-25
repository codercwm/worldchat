<?php
namespace App\Services;

use Hhxsv5\LaravelS\Swoole\WebSocketHandlerInterface;
use Illuminate\Support\Facades\Log;
use Swoole\Http\Request;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;
use App\Events\MessageReceived;
use Hhxsv5\LaravelS\Swoole\Task\Event;
use App\Models\User;

class WebSocketServices implements WebSocketHandlerInterface
{
    // 构造函数即使为空也不能省略
    public function __construct() {}

    // 连接建立时触发
    public function onOpen(Server $server, Request $request)
    {
        Log::info('WebSocket 连接建立:' . $request->fd);
    }

    // 收到消息时触发
    public function onMessage(Server $server, Frame $frame)
    {
        // $frame->fd 是客户端 id，$frame->data 是客户端发送的数据
        Log::info("从 {$frame->fd} 接收到的数据: {$frame->data}");
        //客户端已json方式传数据过来
        $message = json_decode($frame->data);
        // 获取传过来的token进行验证
        if (empty($message->token) || !($user = User::where('api_token', $message->token)->first())) {
            Log::warning("用户" . $message->name . "已经离线，不能发送消息");
            $server->push($frame->fd, "请先登录");  // 告知用户离线状态不能发送消息
        } else {
            // 触发消息接收事件
            $event = new MessageReceived($message, $user->id);
            Event::fire($event);//触发，也就是even($event)，把消息保存到数据库，这里是异步worker的
            unset($message->token);  // 从消息中去掉当前用户令牌字段
            foreach ($server->connections as $fd) {
                if (!$server->isEstablished($fd)) {
                    // 如果连接不可用则忽略
                    continue;
                }
                //向所有客户端进行广播
                $server->push($fd, json_encode($message)); // 服务端通过 push 方法向所有连接的客户端发送数据
            }
        }
    }

    // 连接关闭时触发
    public function onClose(Server $server, $fd, $reactorId)
    {
        Log::info('WebSocket 连接关闭:' . $fd);
    }
}
