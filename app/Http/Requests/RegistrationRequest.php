<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegistrationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => 'required',
            'last_name' => 'required',
            'country' => 'required',
            'birth_date' => 'required|date_format:m/d/Y',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'password_repeat' => 'required|same:password'
        ];
    }
}