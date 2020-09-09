<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Telegram;
use App\TelegramUser;
use Log;
use Telegram\Bot\Keyboard\Keyboard;
use App\Card;

class WebhookController extends Controller
{
    public function hook()
    {
        try {
            $update = Telegram::commandsHandler(true);
            $telegram = new \Telegram\Bot\Api;

            Log::debug('Incomming '.$update->detectType().' from '.$update->getMessage()['from']['username'].' in chat w/ '.$update->getMessage()['chat']['username']."\n".$update->getMessage()['text']);

            if (isset($update['message']) && !TelegramUser::find($update['message']['from']['id'])) {
                Log::debug('New user start '.json_encode($update['message']['from']));
                TelegramUser::create($update['message']['from']);
            }

            switch ($update->detectType()) {
            case 'message':
                $this->processMessage($update);
                break;

            case 'callback_query':
                
                $result = json_decode($update, true);

                $callback_data = $result['callback_query']['data'];
                // $callback_id = $result['callback_query']['message']['chat']['id'];
                // $callback_message_id = $result['callback_query']['message']['message_id'];

                // $arguments = explode(' ', $callback_data);
                // $command = array_shift($arguments);

                // Log::debug('Command: '.$command. "\n". 'Arguments: '.json_encode($arguments));

                // $command_obj = new $command();
                // $command_obj->setArguments($arguments);
                // $command_obj->make($telegram, $update, []);
                
                // $message_update = $update['callback_query'];
                // $message_update['message']['entities'][0] = ['offset'=>0,'length'=>strlen($callback_data),'type'=>'bot_command'];
                // $update['callback_query'] = $message_update;
                // $update['callback_query']['message']['entities'][0] = ['offset'=>0,'length'=>strlen($callback_data),'type'=>'bot_command'];
                $this->triggerCommand($callback_data,$update);

                break;
        }
        } catch (\Throwable $th) {
            Log::error($th);
        }
    }

    private function triggerCommand($command,$update){
        $telegram = new \Telegram\Bot\Api;
        $telegram->getCommandBus()->execute($command, $update, ['offset'=>0,'length'=>strlen($command),'type'=>'bot_command']);
    }

    public function processMessage($update)
    {
        $telegram = new \Telegram\Bot\Api;

        $arguments = explode(' ', $update->getMessage()['text']);
        $cmd = array_shift($arguments);

        if ($user = TelegramUser::find($update->getMessage()['chat']['id'])) {

            // if ($user->chat_id == null){
            //     Log::info('no private chat');
            //     $user->chat_id = $update->getMessage()->chat->id;
            //     $user->save();
            //     Log::info('saved private chat '.$user);
            // }

            if ($user->state != null) {
                $state_args = explode(' ', $user->state);
                $state = array_shift($state_args);
                $update['callback_query'] = [
                    'message'=>$update['message']
                ];
                unset($update['message']);
                switch ($state) {
                    case 'changeCardName':
                        $card = Card::find($state_args[0]);
                        $card->name = $update->getMessage()['text'];
                        $card->save();



                        $telegram->getCommandBus()->execute('card '.$state_args[0], $update, []);
                        break;
                    case 'transaction':
                        $txt = $user->state.' '.$update->getMessage()['text'];
                        $telegram->getCommandBus()->execute($user->state.' '.$update->getMessage()['text'], $update, ['offset'=>0,'length'=>strlen($txt)]);
                        // Telegram::triggerCommand($user->state.' '.$update->getMessage()['text'],$update);
                    break;

                    default:
                        # code...
                        break;
                }
                $user->state = null;
                Log::info('set state to null');
                $user->save();
            }
        }

        // switch ($cmd) {
        //     case 'Balance':
        //         $keyboard = Keyboard::make()->row(
        //             Keyboard::inlineButton(['text' => 'Card 1', 'callback_data' => 'App\Commands\Balance 1']),
        //         )->row(
        //             Keyboard::inlineButton(['text' => 'Back']),
        //         );
        //         $data = [
        //             'text' => 'choose card',
        //             'chat_id' => $update->getChat()['id'],
        //             'reply_markup'=>$keyboard
        //         ];
        //         Telegram::sendMessage($data);
        // break;
        // case ''

        //     default:
        //         # code...
        //         break;
        // }
    }
}
