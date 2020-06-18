<?php

use Illuminate\Support\Facades\Storage;
use Swoole\Http\Request;
use App\Services\WebSocket\WebSocket;
use App\Services\Websocket\Facades\Websocket as WebsocketProxy;
use App\Models\User;
use App\Services\LogService;
use Illuminate\Support\Facades\Cache;
use App\Models\Message;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redis;

/*
|--------------------------------------------------------------------------
| Websocket Routes
|--------------------------------------------------------------------------
|
| Here is where you can register websocket events for your application.
|
*/

WebsocketProxy::on('connect', function (WebSocket $websocket, Request $request) {
    // 发送欢迎信息
    $websocket->setSender($request->fd);

    //
    //$websocket->emit('connect', '欢迎访问聊天室');


    if($login_user = auth('api')->user()){
        // 建立连接时绑定认证用户信息
        $websocket->loginUsing(auth('api')->user());
    }
});

/*WebsocketProxy::on('login', function (WebSocket $websocket, $data) {
    if(!check_login($websocket)) return;
    //$websocket->loginUsing($user);
});*/

WebsocketProxy::on('roomin', function (WebSocket $websocket, $data) {
    //获取房间id
    if (empty($data['room_id'])) {
        return;
    }

    $room_id = $data['room_id'];

    //这个房间和不是数组id的房间未登录用户也可以进入
    if(1==$room_id || !is_numeric($room_id)){
        if(check_login($websocket)){
            $user = User::where('id',$websocket->getUserId())->first();
        }else{
            $user = collect();
            $user->id = $data['user_id'];
            $user->nickname = $data['nickname'];
            $user->avatar = '';
        }
    }else{
        if(!check_login($websocket)) return;
        $user = User::where('id',$websocket->getUserId())->first();
    }

    //重置用户与fd关联
    Redis::command('hset',['socket_id', $user->id, $websocket->getSender()]);

    //用户加入房间
    $room = 'room'.$room_id;
    $websocket->join($room);

    //更新在线用户信息
    $room_user_key = 'online_users_' . $room;
    $online_users = Cache::get($room_user_key);

    $user->avatar = asset_url($user->avatar);
    if ($online_users) {
        $online_users[$user->id] = [
            'nickname' => $user->nickname,
            'avatar' => $user->avatar,
            'id' => $user->id,
        ];
    } else {
        $online_users = [
            $user->id => [
                'nickname' => $user->nickname,
                'avatar' => $user->avatar,
                'id' => $user->id,
            ]
        ];
    }
    Cache::forever($room_user_key, $online_users);

    LogService::write($user->nickname . ' 加入 ' . $room . '房间', 'room_in_out');
    LogService::write($room.'房间里面有nickname : '.json_encode(array_column($online_users,'nickname')), 'room_in_out');

    //广播消息 给房间内所有用户
    //这个to设置room_id是什么原理？
    //to是设置发送放，如果传入int就会被识别为用户的fd进行发送
    //如果传入的是str，就会被识别为room_id，然后它会用room_id获取出这个房间的所有fd进行发送
    $websocket->to($room)->emit('roomin', $online_users);

});

WebsocketProxy::on('roomout', function (WebSocket $websocket, $data) {
    room_out($websocket, $data);
});

WebsocketProxy::on('disconnect', function (WebSocket $websocket, $data) {
    room_out($websocket, $data);
});

WebsocketProxy::on('message',function(WebSocket $websocket,$data){
    //获取房间id
    if (empty($data['room_id'])) {
        return;
    }

    $room_id = $data['room_id'];

    //这个房间和不是数组id的房间未登录用户也可以进入
    if(1==$room_id || !is_numeric($room_id)){
        if(check_login($websocket)){
            $user = User::where('id',$websocket->getUserId())->first();
        }else{
            $user = collect();
            $user->id = $data['user_id'];
            $user->nickname = $data['nickname'];
            $user->avatar = '';
        }
    }else{
        if(!check_login($websocket)) return;
        $user = User::where('id',$websocket->getUserId())->first();
    }
    // 获取消息内容
    $msg = $data['msg'];
    $img = $data['img']??'';
    $time = $data['time'];
    if((empty($msg) && empty($img)) || empty($room_id)){
        return;
    }
    //只保存文字消息到数据库，图片消息在图片上传操作中已保存，无需在这里保存
    if($msg && (1!=$room_id)){
        $message = new Message();
        $message->user_id = $user->id;
        $message->room_id = $room_id;
        $message->msg = $msg;
        $message->img = '';
        $message->save();
    }

    //将消息广播给房间内所有用户
    $room = 'room'.$room_id;

    $message_data = [
        'user_id' => $user->id,
        'nickname' => $user->nickname,
        'avatar' => asset_url($user->avatar),
        'msg' => $msg,
        'img' => $img,
        'room_id' => $room_id,
        'time' => $time
    ];
    $websocket->to($room)->emit('message',$message_data);

});

WebsocketProxy::on('dafeiji', function (WebSocket $websocket, $data) {
    //获取房间id
    if (empty($data['room_id']) || !isset($data['enemy_x'])) {
        return;
    }

    $room = 'room'.$data['room_id'];

    LogService::write("给{$room}返回一个敌机{$data['enemy_id']}--{$data['enemy_x']}",'akdlfjsaklfjsadlkf');
    //$enemy_id = rand(1,12);
    $websocket->to($room)->emit('dafeiji', ['enemy_id'=>$data['enemy_id'],'enemy_x'=>$data['enemy_x']]);
});

WebsocketProxy::on('dafeiji_position', function (WebSocket $websocket, $data) {
    //获取房间id
    if (empty($data['room_id']) || !isset($data['user_id'],$data['position_x'],$data['position_y'])) {
        return;
    }

    $room = 'room'.$data['room_id'];

    $res = [
        'user_id' => $data['user_id'],
        'position_x' => $data['position_x'],
        'position_y' => $data['position_y'],
    ];
    $websocket->to($room)->emit('dafeiji_position', $res);
});

WebsocketProxy::on('dafeiji_dazhao', function (WebSocket $websocket, $data) {
    //获取房间id
    if (!isset($data['user_id'])) {
        return;
    }

    $room = 'room'.$data['room_id'];

    $res = [
        'user_id' => $data['user_id'],
    ];
    $websocket->to($room)->emit('dafeiji_dazhao', $res);
});
