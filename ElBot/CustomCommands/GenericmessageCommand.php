<?php
namespace Longman\TelegramBot\Commands\SystemCommands;

use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Conversation;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Funciones;
use OxMohsen\TgBot\Messages;
class GenericMessageCommand extends SystemCommand




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
        /* El post de la webapp NO llega como un mensaje generico, y NO se puede obtener con getWebAppData() 
        mesage tiene  public function getType(): string pero evidentemente acá no llega */
        $this->debug_a_admins('Paso por genericmessage',json_encode($message));
        $message_text = $message->getText(true);
        if ($message_text=='sarasa')            
            $this->debug_a_admins('Respuesta',json_encode($this->replyToChat('escribieron sarasa')));            
        $web_app_data = $this->getMessage()->getWebAppData();        
        $web_app_data = $this->getMessage()?->getWebAppData()?->getData();  //The Longman\TelegramBot\Entities\Message class has a getWebAppData() method that returns a WebAppData object, which has a getData() method that returns the data sent from the web app.

        // check if $data isn't null and do something with it.
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
