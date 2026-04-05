<?php declare(strict_types=1);

namespace ShahradElahi\DurgerKing;

use TelegramBot\Entities\Update;
use TelegramBot\Request;
use TelegramBot\Telegram;
/**
 * Class App
 *
 * This class is the main class of the application.
 * It is responsible for handling the incoming updates and
 * sending the responses.
 *
 * @link https://core.telegram.org/bots/api#getting-updates
 */
class App extends \TelegramBot\UpdateHandler {

   /**
    * This method is called when the bot receives a new message.
    *
    * @param Update $update
    * @return void
    */
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
   public function __process(Update $update): void {
      //Telegram::setAdminId($_ENV['ADMIN_CHAT_ID']);
      //die( var_dump($update));
      $this->debug_a_admins(':processs($Update)', $update);
      Telegram::setAdminId(662767623);
      if ($update->getMessage()->getText() === '/ping') {
         Request::sendMessage([
            'chat_id' => $update->getMessage()->getChat()->getId(),
            'parse_mode' => 'Markdown',
            'text' => '`Ponga!`',
         ]);
      }
       
      $this->debug_a_admins('Invoice', $update->getMessage()->getInvoice());
      //$this->debug_a_admins('WebAppData', $update->getMessage()->getWebAppData());
      if ( $update->getMessage()->getWebAppData()) {
        $this->debug_a_admins('App esWebAppData', $update->getMessage()->getWebAppData()->getRawData());
      }

      self::addPlugins([
         Plugins\WebService::class,
         Plugins\Commands::class,
         
      ]);
   }

}
/*
        // List of service messages previously handled internally.
        $service_message_getters = [
            'newchatmembers'        => 'getNewChatMembers',
            'leftchatmember'        => 'getLeftChatMember',
            'newchattitle'          => 'getNewChatTitle',
            'newchatphoto'          => 'getNewChatPhoto',
            'deletechatphoto'       => 'getDeleteChatPhoto',
            'groupchatcreated'      => 'getGroupChatCreated',
            'supergroupchatcreated' => 'getSupergroupChatCreated',
            'channelchatcreated'    => 'getChannelChatCreated',
            'migratefromchatid'     => 'getMigrateFromChatId',
            'migratetochatid'       => 'getMigrateToChatId',
            'pinnedmessage'         => 'getPinnedMessage',
            'successfulpayment'     => 'getSuccessfulPayment',
        ];

        foreach ($service_message_getters as $command => $service_message_getter) {
            // Let's check if this message is a service message.
            if ($message->$service_message_getter() === null) {
                continue;
            }
                */