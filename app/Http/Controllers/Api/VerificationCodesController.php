<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\VerificationCodeRequest;

class VerificationCodesController extends Controller
{
    public function store(VerificationCodeRequest $request)
    {
        $phone = $request->phone;
        if(app()->environment() === 'local'){
            $code = '1234';
        }else{
            $code = str_pad(random_int(1, 9999), 4, 0, STR_PAD_LEFT);
        }
        $key = 'verificationCode_'.str_random(15);
        $expiredAt = now()->addMinutes(10);
        \Cache::put($key,['phone' => $phone,'code' => $code],$expiredAt);
        return $this->response->array([
            'key' => $key,
            'expired_at' => $expiredAt->toDateTimeString()
        ])->setStatusCode(201);
    }
}
