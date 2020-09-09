<?php

namespace App\Commands;

use Telegram;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Commands\Command;
use App\Card as CardObject;
use App\TelegramUser;
use Log;

class Card extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "card";

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
        $update = $this->getUpdate();
        $text = "Card not found";
        $keyboard = Keyboard::make()->inline();

        if (isset($this->getArguments()[0])) {
            $card =CardObject::find($this->getArguments()[0]);
            $text = "<b>Card name</b>: ".$card->name.
            "\n<b>Balance</b>: ".$card->balance." ".$card->currency
            ."\n<b>Card Owner</b>: @".TelegramUser::find($update->getMessage()['chat']['id'])->username;
            $keyboard = Keyboard::make()->inline()
            ->row(
                Keyboard::inlineButton(['text' => '💸Make transaction', 'callback_data' => 'transaction '.$card->id]),
            )
            ->row(
                Keyboard::inlineButton(['text' => '⚠️DEBUG: Add 100 USD', 'callback_data' => 'addmoney 100 '.$card->id]),
            )
            ->row(
                Keyboard::inlineButton(['text' => '💅💅💅Show transactions💅💅💅', 'callback_data' => 'your_callback_data']),
            )
            ->row(
                Keyboard::inlineButton(['text' => '🔤Change name', 'callback_data' => 'changecardname '.$card->id]),
            )
            ->row(
                Keyboard::inlineButton(['text' => '⚙️Card Settings', 'callback_data' => 'cardsettings '.$card->id]),
            );
        }

        $keyboard->row(
            Keyboard::inlineButton(['text' => '🔙Back', 'callback_data' => 'cards']),
        );

        $update = $this->getUpdate();

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
