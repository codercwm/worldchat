<?php

namespace App\Http\Requests;


class MessageRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->route()->getActionMethod()){
            case 'history':
                return [
                    'room_id' => ['required','gt:0'],
                    'current' => ['required','gt:0'],
                    'total' => ['required','gt:-1'],
                ];
                break;
        }
    }
}
