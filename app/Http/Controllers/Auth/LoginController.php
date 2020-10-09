<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Log;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $this->validate($request, [
            'email'=>'required|string|email',
            'password'=>'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Authentication passed...
            Log::info('authenticated');
            return redirect()->intended('hub');
        }

        return redirect()->back()->withErrors(['result'=>'You entered wrong login or password'])->withInput();
    }
}
