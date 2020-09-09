<?php

namespace App\Commands;

use Telegram;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Commands\Command;
use App\Card;
use Log;

class AddMoney extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "addmoney";

    /** @var string Command Argument Pattern */
    protected $pattern = '{money} {card_id}';

    /**
     * @var string Command Description
     */
    protected $description = "Show your balance";

    /**
     * @inheritdoc
     */
    public function handle()
    {
        if ($card = Card::find($this->arguments[1])){
            $card->balance += $this->arguments[0];
            $card->save();

            $this->triggerCommand('card '.$card->id);
        }
    }
}
