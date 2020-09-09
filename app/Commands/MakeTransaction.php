<?php

namespace App\Commands;

use Telegram;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Commands\Command;
use App\Card;
use App\TelegramUser;
use Log;

class MakeTransaction extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "transaction";

    /** @var string Command Argument Pattern */
    protected $pattern = '{card_id} {recipient_card_id} {money}';

    /**
     * @var string Command Description
     */
    protected $description = "Make transaction";

    /**
     * @inheritdoc
     */
    public function handle()
    {
        if (count($this->arguments) == 3&&!isset($this->arguments['card_id'])){
            $this->arguments = [
                'card_id'=>$this->arguments[0],
                'recipient_card_id'=>$this->arguments[1],
                'money'=>$this->arguments[2],
            ];
        }
        $update = $this->getUpdate();
        $keyboard = Keyboard::make()->inline()
        ->row(
            Keyboard::inlineButton(['text' => 'Show your wallet', 'callback_data' => 'cards']),
        );

        if (count($this->arguments) != 3) {
            // List of arguments is not full
            $text = "Enter the recipient's nickname and the amount of money to transfer\n\n<i>Example</i>\n<code>@decepti 100</code>";
            $user = TelegramUser::find($update->getMessage()['chat']['id']);
            $user->state = "transaction ".$user->default_card_id;
            $user->save();
        } elseif (!$card = Card::find($this->arguments['card_id'])) {
            // Cannot find sender's card
            $text = "🙁Your card not found";
            Log::error('Card not found. '. $update);
        } elseif ($card->balance < $this->arguments['money']) {
            // Not enough money
            $text = "😭Net enough money to complete transaction\nYour balance: ".$card->getBalance();
        } elseif ($this->arguments['money']<0.1) {
            $text = "😁Thats a lot of data to handle\nminimum transaction is 0.1";
        } elseif (is_numeric($this->arguments['recipient_card_id']) && !$recipient_card =Card::find($this->arguments['recipient_card_id'])) {
            // Recipient card number not found
            $text = "😕Recipient card not found";
            Log::error('Card not found. '. $update);
        } elseif (!is_numeric($this->arguments['recipient_card_id'])) {
            // Found recipient by username
            if (!$recipient = TelegramUser::where('username', ltrim($this->arguments['recipient_card_id'], '@'))->first()) {
                $text = "😒 ".$this->arguments['recipient_card_id']." has not yet used the services of our bank, what a shame";
            } else {
                if (!$recipient->default_card_id) {
                    // Recipient have no default card id
                    // Getting first card available

                    if ($recipient->cards()->count() == 0) {
                        $text = "😕This recipient have no available cards";
                    } else {
                        $recipient_card = $recipient->cards[0];
                        $text = $this->sendTransaction($card, $recipient_card);
                    }
                } else {
                    // Default card is set

                    if (!$recipient_card = $recipient->cards()->find($recipient->default_card_id)) {
                        // Recipient card number not found
                        $text = "😕Recipient card not found";
                        Log::error('Card not found. '. $update);
                    } else {
                        $text = $this->sendTransaction($card, $recipient_card);
                    }
                }
            }
        } else {
            // Transaction correct
            $text = $this->sendTransaction($card, $recipient_card);
        }



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

    public function sendTransaction($card, $recipient_card)
    {
        $card->balance -= $this->arguments['money'];
        $card->save();
        $recipient_card->balance += $this->arguments['money'];
        $recipient_card->save();

        $text = "<b>The transfer was successful!</b>";
        $infotext = "💸Money transfer from @".$card->owner->username.' to @'.$recipient_card->owner->username.
            "\nAmmount: ".$this->arguments['money']." ". $recipient_card->currency;

        $keyboard = Keyboard::make()->inline()
        ->row(
            Keyboard::inlineButton(['text' => 'Show your wallet', 'callback_data' => 'App\Commands\Cards']),
        );
        $data = [
                'text'=>$infotext."\nCard balance: ".$recipient_card->getBalance(),
                'parse_mode'=>'HTML',
                'chat_id'=>$recipient_card->owner->id,
                'reply_markup'=>$keyboard
            ];
        Telegram::sendMessage($data);

        return $text ."\n". $infotext. "\nCard balance: ".$card->getBalance();
    }
}
