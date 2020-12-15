<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CardCreationRequest extends FormRequest
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
            'name' => 'required|string|max:26|min:2',
            'currency' => 'required|string|size:3|in:USD',
            // 'user_id'=>'required|exists:users,id',
            'type' => 'required|in:DEBIT,CREDIT',
            // 'number' => 'required|email|unique:cards,number',
        ];
    }
}