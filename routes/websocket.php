<?php

use Swoole\Http\Request;
use App\Services\WebSocket\WebSocket;
use App\Services\Websocket\Facades\Websocket as WebsocketProxy;
use App\Models\User;
use App\Models\Count;
use App\Services\LogService;
use Illuminate\Support\Facades\Cache;
use App\Models\Message;
use Carbon\Carbon;

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
    $websocket->emit('connect', '欢迎访问聊天室');

});

WebsocketProxy::on('login', function (WebSocket $websocket, $data) {
    if (!empty($data['api_token']) && ($user = User::where('api_token', $data['api_token'])->first())) {

        $websocket->loginUsing($user);
        //获取未读消息
        $res = \App\Models\Count::where('user_id', $user->id)->whereIn('room_id', \App\Models\Count::$ROOMLIST)->get();
        $rooms = [];
        foreach (\App\Models\Count::$ROOMLIST as $roomid) {
            $result = $res->where('room_id', $roomid)->first();
            $roomid = 'room' . $roomid;
            if ($result) {
                $rooms[$roomid] = $result->count;
            } else {
                $rooms[$roomid] = 0;
            }
        }
        $websocket->toUser($user)->emit('count', $rooms);
    } else {
        $websocket->emit('login', '登录后才能进入聊天室1');
    }
});

WebsocketProxy::on('room', function (WebSocket $websocket, $data) {
    if (!empty($data['api_token']) && ($user = User::where('api_token', $data['api_token'])->first())) {

        //获取房间id
        if (empty($data['room_id'])) {
            return;
        }
        $room_id = $data['room_id'];
        //重置用户与fd关联
        \Illuminate\Support\Facades\Redis::command('hset',['socket_id', $user->id, $websocket->getSender()]);

        //将房间该用户未读消息清零
        $count = Count::where('user_id', $user->id)->where('room_id', $room_id)->first();
        if($count){
            $count->count = 0;
            $count->save();
        }

        //用户加入房间
        $room = Count::$ROOMLIST[$room_id];
        $websocket->join($room);

        //更新在线用户信息
        $room_user_key = 'online_users_' . $room;
        $online_users = Cache::get($room_user_key);

        $user->src = $user->avatar;

        if ($online_users) {
            $online_users[$user->id] = $user->toArray();
        } else {
            $online_users = [$user->id => $user->toArray()];
        }
        Cache::forever($room_user_key, $online_users);

        LogService::write($user->nickname . ' 加入 ' . $room . '房间', 'room_in_out');
        LogService::write($room.'房间里面有nickname : '.json_encode(array_column($online_users,'nickname')), 'room_in_out');

        //广播消息 给房间内所有用户
        //这个to设置room_id是什么原理？
        //to是设置发送放，如果传入int就会被识别为用户的fd进行发送
        //如果传入的是str，就会被识别为roomid，然后它会用roomid获取出这个房间的所有fd进行发送
        $websocket->to($room)->emit('room', $online_users);

    } else {
        $websocket->emit('login', '登录后才能进入聊天室2');
    }

});

WebsocketProxy::on('roomout', function (WebSocket $websocket, $data) {
    roomout($websocket, $data);
});

WebsocketProxy::on('disconnect', function (WebSocket $websocket, $data) {
    roomout($websocket, $data);
});

function roomout(WebSocket $websocket, $data) {
    if (!empty($data['api_token']) && ($user = User::where('api_token', $data['api_token'])->first())) {
        if (empty($data['room_id'])) {
            return;
        }
        $room_id = $data['room_id'];
        $room = Count::$ROOMLIST[$room_id];
        // 更新在线用户信息
        $room_users_key = 'online_users_' . $room;
        $online_users = Cache::get($room_users_key);
        if (!empty($online_users[$user->id])) {
            unset($online_users[$user->id]);
            Cache::forever($room_users_key, $online_users);
        }
        LogService::write($user->nickname . ' 退出 ' . $room . '房间', 'room_in_out');
        LogService::write($room.'房间里面有nickname : '.json_encode(array_column($online_users,'nickname')), 'room_in_out');
        $websocket->to($room)->emit('roomout', $online_users);
        $websocket->leave([$room]);
    } else {
        $websocket->emit('login', '登录后才能进入聊天室3');
    }
}


WebsocketProxy::on('message',function(WebSocket $websocket,$data){
    if (!empty($data['api_token']) && ($user = User::where('api_token', $data['api_token'])->first())) {
        // 获取消息内容
        $msg = $data['msg'];
        $room_id = intval($data['room_id']);
        $time = $data['time'];
        if(empty($msg) || empty($room_id)){
            return;
        }
        //保存到数据库
        $message = new Message();
        $message->user_id = $user->id;
        $message->room_id = $room_id;
        $message->msg = $msg;
        $message->img = '';
        $message->created_at = Carbon::now();
        $message->save();

        //将消息广播给房间内所有用户
        $room = Count::$ROOMLIST[$room_id];

        $message_data = [
            'userid' => $user->email,
            'username' => $user->name,
            'src' => $user->avatar,
            'msg' => $msg,
            'img' => '',
            'roomid' => $room_id,
            'time' => $time
        ];
        $websocket->to($room)->emit('message',$message_data);

        //更新所有用户本房间未读消息数
        $user_ids = \Illuminate\Support\Facades\Redis::hgetall('socket_id');
        foreach ($user_ids as $user_id=>$socket_id){
            // 更新每个用户未读消息数并将其发送给对应在线用户
            $result = Count::where('user_id', $user_id)->where('room_id', $room_id)->first();
            if ($result) {
                $result->count += 1;
                $result->save();
                $rooms[$room] = $result->count;
            } else {
                // 如果某个用户未读消息数记录不存在，则初始化它
                $count = new Count();
                $count->user_id = $user->id;
                $count->room_id = $room_id;
                $count->count = 1;
                $count->save();
                $rooms[$room] = 1;
            }
            $websocket->to($socket_id)->emit('count', $rooms);
        }

    }else {
        $websocket->emit('login', '登录后才能进入聊天室');
    }
});
