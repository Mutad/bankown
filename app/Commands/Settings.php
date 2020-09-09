<?php

namespace App\Commands;

use Telegram;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Commands\Command;
use Log;

class Settings extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "settings";

    /**
     * @var string Command Description
     */
    protected $description = "Change settings";

    /**
     * @inheritdoc
     */
    public function handle()
    {
        $update = $this->getUpdate();
        $text = "Choose setting to change";
        $keyboard = Keyboard::make()->inline();

        $keyboard->row(
            Keyboard::inlineButton(['text' => 'Language', 'callback_data' => 'placeholder']),
        )->row(
            Keyboard::inlineButton(['text' => 'Back', 'callback_data' => 'menu']),
        );

        $data = [
            'text'=>$text,
            'parse_mode'=>'HTML',
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
