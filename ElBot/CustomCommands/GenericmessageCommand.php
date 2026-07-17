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

use OxMohsen\TgBot\Messages;
use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Request;

class GenericmessageCommand extends SystemCommand
{
    
    protected $name = 'genericmessage';
    protected $description = 'Handle generic message';
    protected $version = '1.0.0';
    public static function debug_a_admins(   $quien, $msg )
    {
		$bot_api_key  = "676438755:AAG3QBJ5owYiwMjV2wiluXIJB5DGxFyjKbY";
		$bot_username = '@Buchonbot';
		$chatIds = array("662767623"); // Los destinatarios 
    
    	foreach ($chatIds as $chatId) {
        $data = array(   'chat_id' => $chatId,
        'text' => 'Debug '.$quien. '  '.var_export($msg,true) ,
        'parse_mode' => 'HTML' );
         $response = file_get_contents("https://api.telegram.org/bot$bot_api_key/sendMessage?" . http_build_query($data) );
    	}
    	return ; 
    }
    public function execute(): ServerResponse
    {
        $message = $this->getMessage();
        // If a conversation is busy, execute the conversation command after handling the message.
        $conversation = new Conversation(
            $message->getFrom()->getId(),
            $message->getChat()->getId()
        );
        // Fetch conversation command if it exists and execute it.
        if ($conversation->exists() && $command = $conversation->getCommand()) {
            return $this->telegram->executeCommand($command);
        }
        $message_text = $message->getText(true);
        if ($message_text=='sarasa')            
            Funciones::debug_a_admins('Respuesta',json_encode($this->replyToChat('escribieron sarasa')));            
        $web_app_data = $this->getMessage()->getWebAppData();        
        if ($web_app_data) {
            $this->debug_a_admins(   'Webapp', $web_app_data->getData() );
            return $this->replyToChat(
                $web_app_data->getData(),
                ['parse_mode' => 'Markdown']
            );
        }
        return Request::emptyResponse();
    }
}
