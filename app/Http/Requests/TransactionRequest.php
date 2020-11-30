<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
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
            'sender_card_id'=>'required|exists:cards,id',
            'reciever_card_id'=>'required|exists:cards,id|different:sender_card_id',
            'amount'=>'required|min:0.01'
        ];
    }
}