<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class MessageListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $message = $event->getData();
        Log::info(__METHOD__ . ': 开始处理', $message->toArray());
        if ($message && $message->user_id && $message->room_id && ($message->msg || $message->img)) {
            $message->save();
            Log::info(__METHOD__ . ': 处理完毕');
        } else {
            Log::error(__METHOD__ . ': 消息字段缺失，无法保存');
        }
    }
}
