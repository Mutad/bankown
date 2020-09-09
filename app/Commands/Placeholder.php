<?php

namespace App\Commands;

use Telegram;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Commands\Command;

class Placeholder extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "your_callback_data";

    protected $aliases = ['placeholder'];

    /**
     * @var string Command Description
     */
    protected $description = "Placeholder";

    /**
     * @inheritdoc
     */
    public function handle()
    {
        $update = $this->getUpdate();
        
        $keyboard = Keyboard::make()->inline()
        ->row(
            Keyboard::inlineButton(['text' => 'Show menu', 'callback_data' => 'menu']),
        );

        $data = [
            'text'=>'This feature is in development, try it later',
            'message_id'=> $this->getUpdate()->getMessage()['message_id'],
            'chat_id'=>$this->getUpdate()->getChat()['id'],
            'reply_markup'=> $keyboard
        ];

        if ($update->isType('callback_query') && $update->getMessage()['from']['is_bot']) {
            Telegram::editMessageText($data);
        } else {
            Telegram::sendMessage($data);
        }
    }
}
