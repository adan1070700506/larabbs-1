<?php

namespace App\Http\Controllers\Api;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\Api\UsersRequest;

class UsersController extends Controller
{
    public function store(UsersRequest $request)
    {
        $verifyData = \Cache::get($request->verification_key);

        if(!$verifyData){
            return $this->response->error('验证码已失效',422);
        }

        if(!hash_equals($verifyData['code'],$request->verification_code)){
            //验证码作为注册的用户凭证，错误时返回401
            return $this->response->error('验证码错误',401);
        }

        $user = User::create([
            'phone' => $verifyData['phone'],
            'name' => $request->name,
            'password' => bcrypt($request->password),
        ]);

        \Cache::forget($request->verification_key);

        return $this->response->created();
    }


}
