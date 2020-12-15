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
        if (Auth::attempt($attributes, true)) {
            return redirect()->route('hub.main');
        } else {
            return redirect()->back()->withErrors(['result' => 'You entered wrong login or password'])->withInput();
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
