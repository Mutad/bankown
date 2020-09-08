<?php

namespace App\Commands;

use Telegram;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Keyboard\Keyboard;
use Log;

class Multiply extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "mul";

    protected $pattern = '{num1} {num2}';

    /**
     * @var string Command Description
     */
    protected $description = "Multiply two numbers";

    /**
     * @inheritdoc
     */
    public function handle()
    {
        $update = $this->getUpdate();

        $args = $this->getArguments();
        $text = "multiplying";
        if (isset($args['num1']) && isset($args['num2'])) {
            $text = $args['num1'] * $args['num2'];
        }

        $keyboard = Keyboard::make()->inline()->row(
            Keyboard::inlineButton(['text' => 'menu', 'callback_data' => 'App\Commands\Menu']),
            Keyboard::inlineButton(['text' => 'Button 2', 'callback_data' => 'your_callback_data']),
        );

        /** @var Update $response */
        Telegram::sendMessage([
            'text' => 'Test Message pinned to the Keyboard',
            'chat_id' => $update->getChat()->get('id'),
            'reply_markup' => $keyboard
        ]);

        // $this->replyWithMessage(compact('text'));
    }
}
