<?php

/**
 * This file is part of the PHP Telegram Bot example-bot package.
 * https://github.com/php-telegram-bot/example-bot/
 *
 * (c) PHP Telegram Bot Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Generic message command
 *
 * Gets executed when any type of message is sent.
 *
 * In this service-message-related context, we can handle any incoming service-messages.
 */

namespace Longman\TelegramBot\Commands\SystemCommands;

use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Request;

//class DurgerCommand extends SystemCommand
class DurgerCommand extends AdminCommand
{
    /**
     * @var string
     */
    protected $name = 'Durger';

    /**
     * @var string
     */
    protected $description = 'Handle durger message';

    /**
     * @var string
     */
    protected $version = '1.0.0';

    /**
     * Main command execution
     *
     * @return ServerResponse
     */
    public function execute(): ServerResponse
    {
        $message = $this->getMessage();
        $chat    = $message->getChat();
        $user    = $message->getFrom();
        $text    = trim($message->getText(true));
        $chat_id = $chat->getId();
        $user_id = $user->getId();
        // Preparing response
        
        $data['user_id']  = $message->getFrom()->getId();
        $data['chat_id']  = $chat_id;
        //$this->debug_a_admins('Vino ', $message );
        /*
        $wdata = $this->getMessage()?->getWebAppData()?->getData();
        if ($wdata) {
            $this->debug_a_admins(   'Webapp', $wdata );
        }
        */
        /**
         * Catch and handle any service messages here.
         */
        
        $data['text'] = "Durger command executed. This is a service message";
        return Request::sendMessage($data);
        //return Request::emptyResponse();
    }
}
