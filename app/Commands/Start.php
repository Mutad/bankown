<?php

namespace App\Commands;

use Telegram;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Keyboard\Keyboard;
use Log;

class Start extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "start";

    /**
     * @var string Command Description
     */
    protected $description = "Start bot";

    protected $pattern = '{command}';

    /**
     * @inheritdoc
     */
    public function handle()
    {
        $response = $this->getUpdate();
        
        if (isset($this->arguments['command'])) {
            $this->triggerCommand($this->arguments['command']);
        } else {
            $keyboard = Keyboard::make()->row(
                Keyboard::inlineButton(['text' => '/menu']),
            );
            
            
            $this->replyWithMessage([
                'text' => 'Hello, '.$this->getUpdate()->getMessage()['from']['first_name'],
                'reply_markup' => $keyboard
                ]);
        }
    }
}
