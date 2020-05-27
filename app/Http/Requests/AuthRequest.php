<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->route()->getActionMethod()){
            case 'register':
                return [
                    'email' => ['bail', 'required', 'string', 'email', 'max:100', 'unique:users'],
                    'password' => ['required', 'string', 'min:6'],
                    'avatar' => ['bail','active_url','max:255']
                ];
                break;
            case 'login':
                return [
                    'email' => 'required|string|email',
                    'password' => 'required|string',
                ];
                break;

        }

    }
}
