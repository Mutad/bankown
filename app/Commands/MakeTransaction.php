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
    protected $pattern = '{card_id} {recipient_card_id?} {money?}';

    /**
     * @var string Command Description
     */
    protected $description = "Make transaction";

    private $success = false;

    /**
     * @inheritdoc
     */
    public function handle()
    {
        // FIXME idk why but i cant parse arguments
        if (!isset($this->arguments['card_id'])) {
            $data = [];
            switch (count($this->arguments)) {
                case 3:
                    $data['money'] = $this->arguments[2];
                    // no break
                case 2:
                    $data['recipient_card_id'] = $this->arguments[1];
                    // no break
                case 1:
                    $data['card_id'] = $this->arguments[0];
                break;
            }
            $this->arguments = $data;
        }


        $update = $this->getUpdate();

        if (isset($update->getMessage()['chat'])) {
            $user = TelegramUser::find($update->getMessage()['chat']['id']);
        } else {
            $user = TelegramUser::find($update->getMessage()['from']['id']);
        }

        $keyboard = Keyboard::make()->inline();

        if ($update->detectType() == 'chosen_inline_result') {
            $data = [
                'text'=>"No info",
                'parse_mode'=>'HTML',
                'chat_id'=>$this->getUpdate()->getMessage()['from']['id'],
                'reply_markup'=> $keyboard
            ];
        } else {
            $data = [
            'text'=>"No info",
            'parse_mode'=>'HTML',
            'message_id'=> $this->getUpdate()->getMessage()['message_id'],
            'chat_id'=>$this->getUpdate()->getChat()['id'],
            'reply_markup'=> $keyboard
        ];
        }

        if (count($this->arguments) == 0) {
            $data['text'] = "Command usage\n<code>/transaction (your card number) (recipient's card number) (money)\n\n/transaction (your card number) (recipient's username) (money)</code>";
        } elseif (count($this->arguments) == 1) {
            foreach ($user->contacts as $key => $contact) {
                $keyboard->row(
                    Keyboard::inlineButton(['text'=>$contact->username,'callback_data'=>'transaction '.$this->arguments['card_id'].' '.$contact->username])
                );
            }
            // List of arguments is not full
            $data['text'] = "Enter the recipient's nickname or card number";
            if (count($user->contacts)>0) {
                $data['text'] .= "\n\nOr choose from your recent contacts:";
            }
            $user->state = "transaction ".$this->arguments['card_id'];
            $user->save();
        } elseif (count($this->arguments) == 2) {
            $data['text'] = "Enter the amount of money you want to transfer";
            $user->state = "transaction ".$this->arguments['card_id']. ' '. $this->arguments['recipient_card_id'];
            $user->save();
        } elseif (count($this->arguments) == 3) {
            // All arguments are set
            // Validating arguments

            if (!$card = Card::find($this->arguments['card_id'])) {
                // Cannot find sender's card
                $data['text'] = "🙁Your card not found";
                Log::error('Card not found. '. $update);
            } elseif (!$user->cards->contains($card)) {
                $data['text'] = '👮‍♀️You cannot transfer money from someone else\'s card';
            } elseif ($card->balance < $this->arguments['money']) {
                // Not enough money
                $data['text'] = "😭Net enough money to complete transaction\nYour balance: ".$card->getBalance();
            } elseif ($this->arguments['money']<0.1) {
                $data['text'] = "😁Thats a lot of data to handle\nminimum transaction is 0.1";
            // } elseif($this->arguments['card_id'] == $this->arguments['recipient_card_id']){
            //     $data['text'] = "😁You cant make transaction to same card";
            } elseif (is_numeric($this->arguments['recipient_card_id']) && !$recipient_card =Card::find($this->arguments['recipient_card_id'])) {
                // Recipient card number not found
                $data['text'] = "😕Recipient card not found";
                Log::error('Card not found. '. $update);
            } elseif (!is_numeric($this->arguments['recipient_card_id'])) {
                // Found recipient by username
                if (!$recipient = TelegramUser::where('username', ltrim($this->arguments['recipient_card_id'], '@'))->first()) {
                    $data['text'] = "😒 ".$this->arguments['recipient_card_id']." has not yet used the services of our bank, what a shame";
                } else {
                    if (!$recipient->default_card_id) {
                        // Recipient have no default card id
                        // Getting first card available
    
                        if ($recipient->cards()->count() == 0) {
                            $data['text'] = "😕This recipient have no available cards";
                        } else {
                            $recipient_card = $recipient->cards[0];
                            $data['text'] = $this->sendTransaction($card, $recipient_card);
                        }
                    } else {
                        // Default card is set
    
                        if (!$recipient_card = $recipient->cards()->find($recipient->default_card_id)) {
                            // Recipient card number not found
                            $data['text'] = "😕Recipient card not found";
                            Log::error('Card not found. '. $update);
                        } else {
                            $data['text'] = $this->sendTransaction($card, $recipient_card);
                        }
                    }
                }
            } else {
                // Found recipient by card id
                // All arguments are valid
                // Sending transaction
                $data['text'] = $this->sendTransaction($card, $recipient_card);
            }
        }

        $keyboard->row(
            Keyboard::inlineButton(['text' => $this->success?'Cards':'Wallet', 'callback_data' => 'cards']),
        );

        if ($update->isType('callback_query') && $update->getMessage()['from']['is_bot']) {
            Telegram::editMessageText($data);
        } else {
            Telegram::sendMessage($data);
        }
    }

    public function sendTransaction($card, $recipient_card)
    {
        $update = $this->getUpdate();
        $data = [
                'text'=>"No info provided",
                'parse_mode'=>'HTML',
                'chat_id'=>$this->getUpdate()->getMessage()['from']['id']
            ];
            
        if ($card == $recipient_card) {
            $text = "😁You cant make transaction to same card";
        } else {
            $card->balance -= $this->arguments['money'];
            $card->save();
            $recipient_card->balance += $this->arguments['money'];
            $recipient_card->save();

            if (!$recipient_card->owner->contacts->contains($card->owner)) {
                $recipient_card->owner->contacts()->save($card->owner);
            }
            if (!$card->owner->contacts->contains($recipient_card->owner)) {
                $card->owner->contacts()->save($recipient_card->owner);
            }

            $text = "<b>The transfer was successful!</b>";
            $infotext = "💸Money transfer from @".$card->owner->username.' to @'.$recipient_card->owner->username.
            "\nAmmount: ".$this->arguments['money']." ". $recipient_card->currency;

            $recipient_data = [
                'text'=>$infotext."\nCard balance: ".$recipient_card->getBalance(),
                'parse_mode'=>'HTML',
                'chat_id'=>$recipient_card->owner->id
            ];
            Telegram::sendMessage($recipient_data);
            $data['text'] = $text ."\n". $infotext. "\nCard balance: ".$card->getBalance();
            $card->owner->state = null;
            $card->owner->save();
            $text = "🤑<b>Your transaction was successful</b>";
            $this->success = true;
        }
        
        if ($update->isType('callback_query') && $update->getMessage()['from']['is_bot']) {
            Telegram::editMessageText($data);
        } else {
            Telegram::sendMessage($data);
        }
        return $text;
    }
}
