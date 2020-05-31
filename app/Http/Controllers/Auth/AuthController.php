<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\AuthRequest;
use App\Services\ResService;
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
        $params = $request->only(['email','avatar','password']);

        $user = User::create([
            'nickname' => substr($params['email'], 0, strpos($params['email'], '@')),
            'email' => $params['email'],
            'avatar' => $params['avatar'],
            'password' => Hash::make($params['password']),
            'api_token' => Str::random(60)
        ]);
        if ($user) {
            return ResService::data($user)->msg('注册成功，谢谢')->success();
            /*return [
                'errno' => 0,
                'msg' => '注册成功',
                'data' =>  $user
            ];*/
        } else {
            return ResService::msg('保存用户到数据库失败')->fail();
            /*return [
                'errno' => 1,
                'msg' => '保存用户到数据库失败',
                'data' => []
            ];*/
        }
    }

    public function login(AuthRequest $request)
    {
        $params = $request->only(['email','password']);
        $user = User::where('email', $params['email'])->first();
        // 用户校验成功则返回 Token 信息
        if ($user && Hash::check($params['password'], $user->password)) {
            $user->api_token = Str::random(60);
            $user->save();
            return ResService::data($user)->msg('登录成功')->success();
        }
        return ResService::msg('用户名或密码不正确，请重新输入')->fail();

    }

    public function logout(Request $request)
    {
        $user = Auth::guard('auth:api')->user();
        if (!$user) {
            return ResService::msg('用户已退出')->fail();
        }
        $userModel = User::find($user->id);
        $userModel->api_token = null;
        $userModel->save();

        return ResService::msg('退出成功')->success();

    }
}
