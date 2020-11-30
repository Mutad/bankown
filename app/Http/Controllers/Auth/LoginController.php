<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Log;
use Session;
use \App\Http\Requests\LoginRequest;
use Carbon\Carbon;

class LoginController extends Controller
{
    public function show()
    {
        return view('pages.auth.login');
    }

    public function login(Request $request, LoginRequest $requestFields)
    {
        $attributes = $requestFields->only(['email', 'password']);
        Log::info($requestFields->password);
        if ($request->expectsJson()) {
            return $this->apiLogin($request, $attributes);
        } else {
            if (Auth::attempt($attributes, true)) {
                return redirect()->route('hub.main');
            }
            else{
                return redirect()->back()->withErrors(['result'=>'You entered wrong login or password'])->withInput();
            }
        }

        Log::error('Unhandled error at login request '.$attributes);
    }

    private function apiLogin($request, $attributes)
    {
        if (Auth::attempt($attributes)) {
            $user = $request->user();
            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->token;
            $token->expires_at = Carbon::now()->addWeeks(1);
            $token->save();

            Log::info('json');
            return response()->json([
                    'token_type'=>'Bearer', 
                    'access_token' => $tokenResult->accessToken,
                    'user'=>$user,
                    'expires_at' => Carbon::parse(
                        $tokenResult->token->expires_at
                    )->toDateTimeString()
                ]);
        }
        else{
            return response()->json(['error'=>'invalid login or password'], 401);
        }
    }

    public function logout()
    {
        $request->user()->token()->revoke();
        Session::flush();
        Auth::logout();
        return back();
    }
}