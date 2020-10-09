<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
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
    public function store(Request $request)
    {
        
        // Form validation
        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'country'=>'required',
            'birth_date'=>'required|date_format:d/m/Y',
            'email' => 'required|email',
            'password'=>'required',
            'password_repeat'=>'required'
         ]);

         
    }
}
