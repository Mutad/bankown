<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $fillable = [
        'name','balance','currency','user_id','type','number'
    ];

    public function owner()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    public function getBalance(){
        return $this->balance." ".$this->currency;
    }
}