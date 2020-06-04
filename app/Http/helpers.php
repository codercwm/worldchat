<?php

use Illuminate\Support\Facades\Storage;
use App\Services\WebSocket\WebSocket;
use App\Models\User;
use App\Models\Message;
use Illuminate\Support\Facades\Cache;
use App\Services\LogService;

if (! function_exists('asset_url')) {
    function asset_url($url){
        if(empty($url)){
            return '';
        }
        return Storage::url($url);
    }
}


if (! function_exists('room_out')) {
    function room_out(WebSocket $websocket, $data) {
        $user = User::where('id',$websocket->getUserId())->first();

        if (empty($data['room_id'])) {
            return;
        }
        $room_id = $data['room_id'];
        $room = Message::$ROOMLIST[$room_id];
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

    }
}


if (! function_exists('check_login')) {
    function check_login(WebSocket $websocket){
        if($websocket->getUserId()){
            return true;
        }
        $websocket->emit('login', '你还未登录，请先登录');
        return false;
    }
}

