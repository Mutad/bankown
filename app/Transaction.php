<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'sender_card_id','reciever_card_id','amount'
    ];

    public function senderCard()
    {
        return $this->hasOne('App\Card', 'id', 'sender_card_id');
    }

    public function recieverCard()
    {
        return $this->hasOne('App\Card', 'id', 'reciever_card_id');
    }
}