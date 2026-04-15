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
/* RAW
'order_data' => '[{"id":2,"count":5}]',
  'comment' => '',
  'mode' => 'menu',
  'invoice' => 1,
  '_auth' => 'user=%7B%22id%22%3A662767623%2C%22first_name%22%3A%22Dami%C3%A1n%22%2C%22last_name%22%3A%22%22%2C%22username%22%3A%22PerezDamian%22%2C%22language_code%22%3A%22es%22%2C%22allows_write_to_pm%22%3Atrue%2C%22photo_url%22%3A%22https%3A%5C%2F%5C%2Ft.me%5C%2Fi%5C%2Fuserpic%5C%2F320%5C%2FP3a2zzEGYAcXB3ZanpskiS58EhW8UKFJLDuip6tS6H0.svg%22%7D&chat_instance=6982490610179056141&chat_type=sender&auth_date=1776275836&signature=QigGlgNU0TnMXUE8dRaCsE-uImK-L0iPHZ48Sp9hDNv6GDfSTExi3T4VPP2kl764BaTkNjQct8uCjSY-U-aFDA&hash=5f8d84aada56ceb3bb181fb8f713060ed7e72f49f10231f74578494d12ae01d1',
  'method' => 'makeOrder',
*/
      $RAW= $update->getRawData();
      $UPDATE= $update->getMessage();
      $this->debug_a_admins(':processs($Update)', $update);
      $this->debug_a_admins('RAW', $RAW);
      $this->debug_a_admins('UPDATE', $UPDATE);
      
      Telegram::setAdminId(662767623);
      if ($update->getMessage()->getText() === '/ping') {
         Request::sendMessage([
            'chat_id' => $update->getMessage()->getChat()->getId(),
            'parse_mode' => 'Markdown',
            'text' => '`Ponga!`',
         ]);
      } elseif ( $RAW->method =='makeOrder') 
      { 
          Request::sendMessage([
                        'chat_id' => $update->getMessage()->getChat()->getId(),
                        'parse_mode' => 'Markdown',
                        'text' => "Your order has been placed successfully! 🍟"
                    ]);
         Response::send(StatusCode::OK,[
                        "ok"=> true,
                        "invoice_url" => "https://t.me/$NG4UvISlmUpdIwAAFps900FiGKM"
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