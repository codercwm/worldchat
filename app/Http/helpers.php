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
        //获取房间id
        if (empty($data['room_id'])) {
            return;
        }
        $room_id = $data['room_id'];

        //这个房间未登录用户也可以进入
        if(1==$room_id){
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

        $room = 'room'.$room_id;
        // 更新在线用户信息
        $room_users_key = 'online_users_' . $room;
        $online_users = Cache::get($room_users_key);

        if (isset($online_users[$user->id])) {
            unset($online_users[$user->id]);
            Cache::forever($room_users_key, $online_users);
        }
        $websocket->to($room)->emit('roomout', $online_users);
        $websocket->leave([$room]);

    }
}

if (! function_exists('room_in')) {

}

if (! function_exists('check_login')) {
    function check_login(WebSocket $websocket){
        if($websocket->getUserId()){
            return true;
        }
        return false;
    }
}

