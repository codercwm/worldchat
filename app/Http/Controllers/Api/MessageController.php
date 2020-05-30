<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\MessageRequest;
use App\Http\Resources\MessageResource;
use App\Models\Message;
use App\Services\LogService;
use App\Services\ResService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MessageController extends ApiBaseController
{
    public function history(MessageRequest $request){
        $params = $request->only(['room_id','current','total']);
        $room_id = intval($params['room_id']);
        $current = intval($params['current']);
        $total = intval($params['total']);

        //获取消息总数
        $message_total = Message::where('room_id',$room_id)->count();
        $limit = 20;
        $skip = ($current - 1) * $limit;

        //分页查询
        $message_data = Message::where('room_id',$room_id)->skip($skip)->take($limit)->orderBy('created_at')->get();

        $res = [
            'list' => MessageResource::collection($message_data),
            'total' => $total,
            'current' => $current,
        ];

        return ResService::data($res)->success();
    }
}
