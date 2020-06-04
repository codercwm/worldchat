<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

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
            'avatar' => asset_url($this->user->avatar),
            'msg' => $this->msg,
            'img' => asset_url($this->img),
            'room_id' => $this->room_id,
            'time' => $this->created_at,
        ];
    }
}
