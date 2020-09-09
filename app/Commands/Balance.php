<?php

namespace App\Commands;

use Telegram;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Commands\Command;
use Log;

class Balance extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "balance";

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
    }
}
