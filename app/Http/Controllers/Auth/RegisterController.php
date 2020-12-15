<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\RegisterUser;
use App\User;
use Log;
use Hash;
use Carbon\Carbon;
use App\Http\Requests\RegistrationRequest;

class RegisterController extends Controller
{
    use RegisterUser;

    public function create()
    {
        return view('pages.auth.register');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(RegistrationRequest $requestFields)
    {
        $user = $this->registerUser($requestFields);

        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();

        return response()->json([
            'access_token' => 'Bearer ' . $tokenResult->accessToken,
            'user' => $user,
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ], 200);
    }
}
