<?php

namespace App\Commands;

use Telegram;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Commands\Command;
use App\TelegramUser;
use App\Card;
use Log;

class CardName extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "changecardname";

    /** @var string Command Argument Pattern */
    protected $pattern = '{card_id}';

    /**
     * @var string Command Description
     */
    protected $description = "Change card name";

    /**
     * @inheritdoc
     */
    public function handle()
    {
        $text = "User not found";
        $update = $this->getUpdate();

        if ($user = TelegramUser::find($update->getMessage()['chat']['id'])) {
            $text = "Send me new card name";
            $user->state = "changeCardName ".$this->arguments[0];
            $user->save();
        }

        // $keyboard = Keyboard::make()->inline()
        // ->row(
        //     Keyboard::inlineButton(['text' => 'back', 'callback_data' => 'App\Commands\Card '.$this->arguments[0]]),
        // );

        $data = [
            'text'=>$text,
            'message_id'=> $this->getUpdate()->getMessage()['message_id'],
            'chat_id'=>$this->getUpdate()->getChat()['id'],
            // 'reply_markup'=> $keyboard
        ];

        if ($update->isType('callback_query') && $update->getMessage()['from']['is_bot']) {
            Telegram::editMessageText($data);
        } else {
            Telegram::sendMessage($data);
        }
    }
}
