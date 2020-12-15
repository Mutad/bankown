<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Telegram;
use App\TelegramUser;
use Log;
use Telegram\Bot\Keyboard\Keyboard;
use App\Card;
use Illuminate\Support\Str;

use Telegram\Bot\Objects\InlineQuery\InlineQueryResultArticle;
use Telegram\Bot\Objects\InputContent\InputTextMessageContent;

class WebhookController extends Controller
{
    public function hook()
    {
        try {
            $update = Telegram::commandsHandler(true);
            $telegram = new \Telegram\Bot\Api;

            if (isset($update['inline_query'])) {
                $this->processInlineQuery($update);
                return;
            }

            Log::info('Incomming ' . $update->detectType() . ' from ' . $update->getMessage()['from']['username']);

            if (isset($update['message']) && !TelegramUser::find($update['message']['from']['id'])) {
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


                    // $command_obj = new $command();
                    // $command_obj->setArguments($arguments);
                    // $command_obj->make($telegram, $update, []);

                    // $message_update = $update['callback_query'];
                    // $message_update['message']['entities'][0] = ['offset'=>0,'length'=>strlen($callback_data),'type'=>'bot_command'];
                    // $update['callback_query'] = $message_update;
                    // $update['callback_query']['message']['entities'][0] = ['offset'=>0,'length'=>strlen($callback_data),'type'=>'bot_command'];
                    $this->triggerCommand($callback_data, $update);

                    break;
                case 'chosen_inline_result':
                    $result = $update['chosen_inline_result'];
                    $this->triggerCommand('transaction ' . $result['result_id'] . ' ' . $result['query'], $update);
                    break;
            }
        } catch (\Throwable $th) {
            Log::error($th);
        }
    }

    private function triggerCommand($command, $update)
    {
        $telegram = new \Telegram\Bot\Api;
        $telegram->getCommandBus()->execute($command, $update, ['offset' => 0, 'length' => strlen($command), 'type' => 'bot_command']);
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
                    'message' => $update['message']
                ];
                unset($update['message']);
                switch ($state) {
                    case 'changeCardName':
                        $card = Card::find($state_args[0]);
                        $card->name = $update->getMessage()['text'];
                        $card->save();
                        $telegram->getCommandBus()->execute('card ' . $state_args[0], $update, []);
                        break;
                    case 'transaction':
                        $txt = $user->state . ' ' . $update->getMessage()['text'];
                        $telegram->getCommandBus()->execute($user->state . ' ' . $update->getMessage()['text'], $update, ['offset' => 0, 'length' => strlen($txt)]);
                        break;

                    default:
                        # code...
                        break;
                }
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

    private function processInlineQuery($update)
    {
        $telegram = new \Telegram\Bot\Api;

        $inlineQuery = $update['inline_query'];
        $user = TelegramUser::find($inlineQuery['from']['id']);
        $inlineQueryResultArticles = [];
        $answer = [
            'cache_time' => '1',
            'inline_query_id' => $update->inline_query->id,
        ];
        if (count($user->cards) == 0) {
            $answer['switch_pm_text'] = "You have no cards. Open one right now.";
            $answer['switch_pm_parameter'] = "opencard";
        }

        if ($inlineQuery['query'] == '' || !isset($inlineQuery['query'])) {
            array_push(
                $inlineQueryResultArticles,
                new InlineQueryResultArticle(
                    [
                        'id' => '123',
                        'title' => 'Enter recipient username or card id',
                        'thumb_url' => 'https://mutad.ml/logo.png',
                        'description' => '(username) (money)',
                        'input_message_content' => new InputTextMessageContent([
                            'message_text' => 'Provide valid data to make request',
                        ]),
                    ]
                )
            );
        } else {
            $params = explode(' ', $inlineQuery['query']);

            switch (count($params)) {
                case 1:
                    array_push(
                        $inlineQueryResultArticles,
                        new InlineQueryResultArticle(
                            [
                                'id' => '123',
                                'title' => 'Enter amount of money to transfer to ' . $params[0],
                                'thumb_url' => 'https://mutad.ml/logo.png',
                                'description' => '(money)',
                                'input_message_content' => new InputTextMessageContent([
                                    'message_text' => 'Provide valid data to make request',
                                ]),
                            ]
                        )
                    );
                    break;
                case 2:
                    foreach ($user->cards as $key => $card) {
                        array_push($inlineQueryResultArticles, new InlineQueryResultArticle(
                            [
                                'id' => $card->id,
                                'thumb_url' => 'https://img.icons8.com/ios/100/000000/money-transfer.png',
                                'title' => $card->name . ' ' . $card->getBalance(),
                                'description' => 'Transfer from ' . $card->name . ' to ' . $params[0],
                                'input_message_content' => new InputTextMessageContent([
                                    'message_text' => 'Transfer ' . $params[1] . ' ' . $card->currency . ' to ' . $params[0] . "\nYour transaction is now processing\nTransaction key:" . Str::uuid(),
                                ]),
                            ]
                        ));
                    }
                    break;

                default:
                    # code...
                    break;
            }
        }



        // new InlineQueryResultArticle([
        //     'id' => 123,
        //     'thumb_url'=>'https://img.icons8.com/ios/100/000000/money-transfer.png',
        //     'title' => 'test title 1',
        //     'input_message_content' => new InputTextMessageContent([
        //         'message_text' => 'test content 1',
        //     ]),
        //     'description'=>'desc'
        // ]),
        // new InlineQueryResultArticle([
        //     'id' => 456,
        //     'title' => 'test title 2',
        //     'input_message_content' => new InputTextMessageContent([
        //         'message_text' => 'test content 2',
        //     ])
        // ])
        $answer['results'] = json_encode($inlineQueryResultArticles);
        $result = $telegram->answerInlineQuery($answer);
    }
}
