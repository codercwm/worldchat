<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        return [
            'id' => $this->id,
            'user_id' => $this->user->id,
            'nickname' => $this->user->nickname,
            'avatar' => $this->user->avatar,
            'msg' => $this->msg,
            'img' => $this->img,
            'room_id' => $this->room_id,
            'time' => $this->created_at,
        ];
    }
}
