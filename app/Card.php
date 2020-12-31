<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $fillable = [
        'name', 'balance', 'currency', 'user_id', 'type', 'number'
    ];

    public function owner()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    public function getBalance()
    {
        return $this->balance . " " . $this->currency;
    }

    public function sentTransactions()
    {
        return
            $this->hasMany('App\Transaction', 'sender_card_id', 'id');
    }

    public function recievedTransactions()
    {
        return
            $this->hasMany('App\Transaction', 'reciever_card_id', 'id');
    }

    public function getTransactionsAttribute($value)
    {
        // There two calls return collections
        // as defined in relations.
        $sentTransactions = $this->sentTransactions;
        $recievedTransactions = $this->recievedTransactions;

        // Merge collections and return single collection.
        return $sentTransactions->merge($recievedTransactions);
    }
}