<?php

namespace App\Traits;

use Log;

trait RegisterUser
{
    public function registerUser($fields)
    {
        $user = \App\User::create([
            'first_name' => trim($fields->first_name),
            'last_name' => trim($fields->last_name),
            'country' => $fields->country,
            'birth_date' => $fields->birth_date,
            'email' => strtolower($fields->email),
            'password' => bcrypt($fields->password),
        ]);
        return $user;
    }
}
