<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Log;
use \App\Http\Requests\LoginRequest;


class LoginController extends Controller
{
    public function show()
    {
        return view('pages.auth.login');
    }

    public function login(LoginRequest $requestFields)
    {
        // $validator = $this->validate($request, [
        //     'email'=>'required|string|email',
        //     'password'=>'required|string',
        // ]);

        $attributes = $requestFields->only(['email', 'password']);
        // dd($attributes);
        if (Auth::attempt($attributes,true)) {
            return redirect()->route('hub.main');
        }

        return redirect()->back()->withErrors(['result'=>'You entered wrong login or password'])->withInput();
    }

    public function logout()
    {
        Session::flush();
        Auth::logout();
        return back();
    }
}
