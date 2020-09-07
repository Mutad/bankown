<?php

namespace App\Commands;

use Telegram;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Commands\Command;
use App\Card as CardObject;
use App\TelegramUser;
use Log;

class CardSettings extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "cardsettings";

    /** @var string Command Argument Pattern */
    protected $pattern = '{card_id}';

    /**
     * @var string Command Description
     */
    protected $description = "Change card settings";

    /**
     * @inheritdoc
     */
    public function handle()
    {
        $update = $this->getUpdate();
        $text = "Card not found";
        $keyboard = Keyboard::make()->inline();
        $user = TelegramUser::find($update->getMessage()['chat']['id']);

        if (isset($this->getArguments()[0])) {
            $card =CardObject::find($this->getArguments()[0]);
            $text = "<b>Card settings</b>"
            ."\nID: ".$card->id
            ."\nName: ".$card->name
            ."\nOwner: @".$user->username
            ."\nDefault: ". ($user->default_card_id == $card->id?"Yes":"No");
            $keyboard = Keyboard::make()->inline()
            ->row(
                Keyboard::inlineButton(['text' => 'Change name', 'callback_data' => 'App\Commands\CardName '.$card->id]),
            )
            ->row(
                Keyboard::inlineButton(['text' => ($user->default_card_id==$card->id?'✅':'').'Make default', 'callback_data' => 'App\Commands\MakeDefaultCard '.$card->id]),
            );
        }
        $keyboard->row(
            Keyboard::inlineButton(['text' => 'Back', 'callback_data' => 'App\Commands\Card '.$card->id]),
        );

        $update = $this->getUpdate();

        $data = [
            'text'=>$text,
            'parse_mode'=>'HTML',
            'message_id'=> $this->getUpdate()->getMessage()['message_id'],
            'chat_id'=>$this->getUpdate()->getChat()['id'],
            'reply_markup'=> $keyboard
        ];

        if ($update->isType('callback_query')) {
            Telegram::editMessageText($data);
        } else {
            Telegram::sendMessage($data);
        }
    }
}
