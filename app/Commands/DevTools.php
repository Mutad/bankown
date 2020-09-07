<?php

namespace App\Commands;

use Telegram;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Commands\Command;
use Log;

class DevTools extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "balance";

    /** @var string Command Argument Pattern */
    protected $pattern = '{card_id}';

    /**
     * @var string Command Description
     */
    protected $description = "Show your balance";

    /**
     * @inheritdoc
     */
    public function handle()
    {
        $text = "Select your card";
        $keyboard = Keyboard::make()->inline()->row(
            Keyboard::inlineButton(['text' => 'Add 1000$', 'callback_data' => 'App\Commands\AddMoney 1000']),
        )->row(
            Keyboard::inlineButton(['text' => 'back', 'callback_data' => 'App\Commands\Menu']),
        );

        Telegram::editMessageText([
            'text'=>$text,
            'message_id'=> $this->getUpdate()->getMessage()['message_id'],
            'chat_id'=>$this->getUpdate()->getChat()['id'],
            'reply_markup'=> $keyboard
        ]);
    }
}
