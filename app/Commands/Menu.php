<?php

namespace App\Commands;

use Telegram;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Commands\Command;

class Menu extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "menu";

    /**
     * @var string Command Description
     */
    protected $description = "Open main menu";

    /**
     * @inheritdoc
     */
    public function handle()
    {
        $keyboard = Keyboard::make()->inline()
        ->row(
            Keyboard::inlineButton(['text' => 'Select card', 'callback_data' => 'cards']),
            // Keyboard::inlineButton(['text' => 'Dev tools', 'callback_data' => 'App\Commands\DevTools']),
        )->row(
            Keyboard::inlineButton(['text' => '⚙️Settings', 'callback_data' => 'your_callback_data']),
        );

        if ($this->getUpdate()->isType('callback_query')) {
            Telegram::editMessageText([
            'text'=>'Menu',
            'message_id'=> $this->getUpdate()->getMessage()['message_id'],
            'chat_id'=>$this->getUpdate()->getChat()['id'],
            'reply_markup'=> $keyboard
        ]);
        }
        else{
            $this->replyWithMessage([
                'text'=>'Menu',
                'reply_markup'=> $keyboard
            ]);
        }
    }
}
