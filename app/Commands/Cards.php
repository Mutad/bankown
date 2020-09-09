<?php

namespace App\Commands;

use Telegram;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Commands\Command;
use App\TelegramUser;
use Log;

class Cards extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "cards";

    /**
     * @var string Command Description
     */
    protected $description = "Show all your cards";

    /**
     * @inheritdoc
     */
    public function handle()
    {
        $update = $this->getUpdate();

        $text = "Select your card";
        $keyboard = Keyboard::make()->inline();

        if ($user = TelegramUser::find($update->getMessage()['chat']['id'])) {
            $user->state = null;
            $user->save();
            if (!$user->default_card_id){
                $text .= "\n<b>⚠️ Your default card is not set, you cannot receive transactions. To make a default card go to card settings</b>";
            }
            foreach ($user->cards as $key => $card) {
                $keyboard->row(
                    Keyboard::inlineButton(
                        ['text' => ($card->id == $user->default_card_id?'(default) ':''). $card->name.
                    ' - '. $card->balance.' '. $card->currency,
                    'callback_data' => 'card '.$card->id]
                    ),
                );
            }
        }

        $keyboard
        ->row(
            Keyboard::inlineButton(['text' => 'Open new card', 'callback_data' => 'opencard']),
        )
        ->row(
            Keyboard::inlineButton(['text' => 'back', 'callback_data' => 'menu']),
        );



        $data = [
            'text'=>$text,
            'parse_mode'=>"HTML",
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
