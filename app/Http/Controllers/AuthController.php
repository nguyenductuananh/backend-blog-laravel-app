<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{


    public function login(Request $request)
    {
        $validate = Validator::make($request->all(), ['username' => 'required', 'password' => 'required']);

        if($validate->fails()) {
            return $validate->getMessageBag();
        } else {
            $user = ['username'  => $request->username, 'password' => $request->password];

            if($token = auth()->setTTL(5)->attempt($user, true)) {
                return response()->json(['token' => $token, 'expires' => date("Y-m-d H:i:s", date_timestamp_get(now()) + auth()->factory()->getTTL() * 60)]);
            } else {
                return response()->json(['message' => "Login fail"], 401);
            }
        }
    }

    public function logout(Request $request) {
        auth()->logout();

        return ['message'=>'Logout successfully!!!!'];
    }
}
