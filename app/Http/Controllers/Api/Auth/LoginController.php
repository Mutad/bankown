<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use Log;

class LoginController extends Controller
{
    public function login(Request $request, LoginRequest $requestFields)
    {
        $attributes = $requestFields->only(['email', 'password']);

        if (Auth::attempt($attributes)) {
            // Login is successful
            $user = $request->user();

            //Token creation
            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->token;
            $token->expires_at = Carbon::now()->addWeeks(1);
            $token->save();

            return response()->json([
                'token_type' => 'Bearer',
                'access_token' => $tokenResult->accessToken,
                'user' => $user,
                'expires_at' => Carbon::parse(
                    $tokenResult->token->expires_at
                )->toDateTimeString()
            ]);
        } else {
            // Login is unsucessful
            return response()->json(['error' => 'invalid login or password'], 401);
        }
    }
}
