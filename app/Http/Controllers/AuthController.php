<?php

namespace App\Http\Controllers;

use App\Enums\HttpStatusCode;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public function __construct()
    {
        parent::__construct(null);
    }
    public function login(Request $request)
    {
        $validate = Validator::make($request->all(), ['username' => 'required', 'password' => 'required']);

        if ($validate->fails()) {
            return $this->formatJson($validate->getMessageBag(), HttpStatusCode::UNPROCESSABLE_ENTITY);
        } else {
            $user = ['username' => $request->username, 'password' => $request->password];

            if ($token = auth()->setTTL(24 * 60)->attempt($user, true)) {
                return $this->formatJson(['token' => $token, 'expires' => date("Y-m-d H:i:s", date_timestamp_get(now()) + auth()->factory()->getTTL() * 60)]);
            } else {
                return $this->formatJson(['message' => "Login fail"], HttpStatusCode::FORBIDDEN);
            }
        }
    }

    public function logout()
    {
        auth()->logout();

        return $this->formatJson(['message' => "Logout successfully !"]);
    }

    public function userInformation(Request $request)
    {
        return $this->formatJson(Account::with('user')->where('id', auth()->user()->id)->get()->first());
    }
}
