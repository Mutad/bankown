<?php

namespace App\Commands;

use Telegram;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Commands\Command;
use App\Card as CardObject;
use App\TelegramUser;
use DB;
use Log;

class Status extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "status";

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
        if (DB::connection()){
            $text = "✅200 OK";
        }
        else{
            $text = "❗️Database connection could not be enstablished 500";
        }
        $text .= "\nversion ".trim(exec('git describe'));
        $keyboard = Keyboard::make()->inline();

        $keyboard->row(
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
