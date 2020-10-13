<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TelegramUser extends Model
{
    protected $fillable = [
        'id','first_name','last_name','username','is_bot','language_code','chat_id','default_card_id'
    ];

   public function cards()
   {
       return null;
       return $this->hasMany('App\Card', 'telegram_user_id', 'id');
   }

   public function contacts()
   {
       return $this->belongsToMany('App\TelegramUser','contacts','user_id','contact_id');   
   }
}
