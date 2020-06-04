<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Services\LogService;
use App\Services\ResService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;

class FileController extends Controller
{
    public function chatsimg(Request $request)
    {
        if (!$request->hasFile('file') || !$request->file('file')->isValid() || !$request->has('room_id')) {
            return ResService::msg('无效的参数（房间号/图片文件为空或者无效）')->fail();
        }

        $image = $request->file('file');
        $filename = Uuid::uuid1()->getHex() . '.' . $image->extension();
        $path = $image->storeAs('images/chats' . date('Y-m-d', time()), $filename, ['disk' => 'public']);
        if ($path) {
            // 图片上传成功则将对应图片消息保存到 messages 表
            $message = new Message();
            $message->user_id = auth('api')->id();
            $message->room_id = $request->post('room_id');
            $message->msg = '';  // 文本消息留空
            $message->img = $path;
            $message->created_at = Carbon::now();
            $message->save();
            return ResService::msg('保存成功')->success();
        } else {
            return ResService::msg('文件上传失败，请重试')->fail();
        }
    }

    public function avatar(Request $request){
        if (!$request->hasFile('file') || !$request->file('file')->isValid()) {
            return ResService::msg('无效的参数（头像图片为空或者无效）')->fail();
        }
        $image = $request->file('file');
        $filename = Uuid::uuid1()->getHex() . '.' . $image->extension();
        $path = $image->storeAs('images/avatars/' . date('Y-m-d', time()), $filename, ['disk' => 'public']);
        if ($path) {
            // 保存用户头像信息到数据库
            $user = auth('api')->user();
            $user->avatar = $path;
            $user->save();
            return ResService::msg('保存成功')->data(['url' => asset_url($path)])->success();
        } else {
            return ResService::msg('文件上传失败，请重试')->fail();
        }
    }
}
