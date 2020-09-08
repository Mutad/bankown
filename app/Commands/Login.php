<?php

namespace App\Commands;

use Telegram;
use Telegram\Bot\Commands\Command;
use Log;

class Login extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "login";

    /**
     * @var string Command Argument Pattern
     */
    protected $pattern = '{id}';

    /**
     * @var string Command Description
     */
    protected $description = "Sign in to your account";

    /**
     * @inheritdoc
     */
    public function handle()
    {
        $response = $this->getUpdate();
        $text = "Log in to perfect bank";

        $args = $this->getArguments();

        $this->replyWithMessage(compact('text'));
    }
}
