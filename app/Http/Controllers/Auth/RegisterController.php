<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\RegisterUser;
use App\User;
use Hash;
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
    public function store(RegistrationRequest $requestFields)
    {
        
        // Form validation
        // $validator = $this->validate($request, [
        //     'first_name' => 'required',
        //     'last_name' => 'required',
        //     'country'=>'required',
        //     'birth_date'=>'required|date_format:d/m/Y',
        //     'email' => 'required|email|unique:users,email',
        //     'password'=>'required|min:6',
        //     'password_repeat'=>'required|same:password'
        //  ]);

        //  User::create([
        //      'first_name'=>trim($request->first_name),
        //      'last_name'=>trim($request->last_name),
        //      'country'=>$request->country,
        //      'birth_date'=>$request->birth_date,
        //      'email'=>strtolower($request->email),
        //      'password'=>Hash::make($request->country),
        //  ]);
        // User::create($requestFields);
        $user = $this->registerUser($requestFields);
         return redirect()->route('hub.main');
    }
}
