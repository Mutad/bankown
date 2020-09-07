<?php

namespace App\Commands;

use Telegram;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Keyboard\Keyboard;
use Log;
use App\TelegramUser;

class MakeDefaultCard extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "MakeDefaultCard";

    protected $pattern = "{card_id}";

    /**
     * @var string Command Description
     */
    protected $description = "Start bot";

    /**
     * @inheritdoc
     */
    public function handle()
    {
        $update = $this->getUpdate();

        if ($user = TelegramUser::find($update->getMessage()['chat']['id'])){
            $user->default_card_id = $this->arguments[0];
            $user->save();
        }

        $this->triggerCommand('cardsettings '.$this->arguments[0]);
    }
}
