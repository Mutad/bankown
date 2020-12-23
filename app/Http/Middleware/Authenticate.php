<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Auth;
use Log;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (Auth::user() == null){
            if ($request->expectsJson()) {
                return response()->json(['message'=>'unauthenticated'], 403);
            }
            else{
                return redirect()->route('welcome');   
            }
        }
    }
}
