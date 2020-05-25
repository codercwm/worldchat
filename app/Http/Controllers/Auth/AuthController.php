<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\AuthRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends ApiBaseController
{
    public function __construct()
    {
        $this->middleware('auth:api')->only('logout');
    }

    public function register(AuthRequest $request)
    {
        // 创建用户并返回信息
        return User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'api_token' => Str::random(60)
        ]);
    }

    public function login(AuthRequest $request)
    {
        //
        $email = $request->input('email');
        $password = $request->input('password');
        $user = User::where('email', $email)->first();
        // 进行验证
        if ($user && Hash::check($password, $user->password)) {
            //登录后更新token
            $user->api_token = Str::random(60);
            $user->save();
            return response()->json(['user' => $user, 'success' => true]);
        }

        return  response()->json(['success' => false]);
    }

    public function logout(Request $request)
    {
        $user = Auth::guard('auth:api')->user();
        $userModel = User::find($user->id);
        $userModel->api_token = null;//退出登录情况token
        $userModel->save();
        return response()->json(['success' => true]);
    }
}
