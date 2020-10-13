<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $fillable = [
        'name','telegram_user_id','balance','currency','type'
    ];

    public function owner()
    {
        return $this->hasOne('App\TelegramUser', 'id', 'telegram_user_id');
    }

    public function getBalance(){
        return $this->balance." ".$this->currency;
    }
}
