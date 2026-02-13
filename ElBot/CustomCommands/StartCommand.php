<?php
namespace Longman\TelegramBot\Commands\UserCommands;
use Longman\TelegramBot\ChatAction;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Entities\UserProfilePhotos;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Utilities\Routing\Response;
use Utilities\Routing\Utils\StatusCode;

class StartCommand extends UserCommand
{
    protected $name = 'Start';
    protected $description = 'Arranca el bot, muestra el QR';
    protected $usage = '/Start';
    protected $version = '1.2.0';
    protected $private_only = false;
    public function execute(): ServerResponse
    {
        $message = $this->getMessage();

        $from       = $message->getFrom();
        $user_id    = $from->getId();
        $chat_id    = $message->getChat()->getId();
        $message_id = $message->getMessageId();
        $data = [
            'chat_id'             => $chat_id,
 //           'reply_to_message_id' => $message_id,
            'parse_mode' => 'HTML',
        ];

         // Create the keyboard buttons
        $button1 = new InlineKeyboardButton(['text' => 'Option A', 'url' => 'https://bots.perezcompany.com.ar/durger-king/public']);
        $button2 = new InlineKeyboardButton(['text' => 'Option B', 'callback_data' => 'option_B']);

        // Create the inline keyboard and add a row of buttons
        $inline_keyboard = new InlineKeyboard($button1, $button2);


            Request::sendMessage([
                'chat_id' => $message->getChat()->getId(),
                'parse_mode' => 'HTML', //ParseMode::MARKDOWN,
                'text' => "*Let's get started* 🍟 \n\nPlease tap the button below to order your perfect lunch!",
                'reply_markup' => $inline_keyboard,
            ]);

        // Send chat action "typing..."
        Request::sendChatAction([
            'chat_id' => $chat_id,
            'action'  => ChatAction::TYPING,
        ]);

        $caption = sprintf(
            'Your Id: %d' . PHP_EOL .
            'Name: %s %s' . PHP_EOL .
            'Username: %s',
            $user_id,
            $from->getFirstName(),
            $from->getLastName(),
            $from->getUsername()
        );
        $caption = "Bienvenido a Gasav ".trim($from->getFirstName().' '.$from->getLastName())." ($user_id ".  $from->getUsername().")";

        // Fetch the most recent user profile photo
        $limit  = 1;
        $offset = null;

        $user_profile_photos_response = Request::getUserProfilePhotos([
            'user_id' => $user_id,
            'limit'   => $limit,
            'offset'  => $offset,
        ]);


        
        
        
        
        $buchon = array(   'chat_id' => 662767623,
        'text' => $caption,
        'parse_mode' => 'HTML' );
        $bot_api_key  = "676438755:AAG3QBJ5owYiwMjV2wiluXIJB5DGxFyjKbY";
		$bot_username = '@Buchonbot';

        $buchon['text']=$caption;        
        $response = file_get_contents("https://api.telegram.org/bot$bot_api_key/sendMessage?" . http_build_query($buchon) );
        

        if ($user_profile_photos_response->isOk()) {
            /** @var UserProfilePhotos $user_profile_photos */
            $user_profile_photos = $user_profile_photos_response->getResult();

            if ($user_profile_photos->getTotalCount() > 0) {
                $photos = $user_profile_photos->getPhotos();
                // Get the best quality of the profile photo
                $photo   = end($photos[0]);
                $file_id = $photo->getFileId();
                $data['photo']   = $file_id;
                $data['caption'] = $caption;
                Request::sendPhoto($data);
            }
        }
        else
        {
            // No Photo just send text
            $data['text'] = $caption;

           Request::sendMessage($data);
        }
        $texto1="<b>¡Bienvenidos a Nuestro Club ".trim($from->getFirstName().' '.$from->getLastName())." !</b>".PHP_EOL.
"Estamos ubicados sobre la avenida costanera Almte. Brown parador 2 frente al Palacio Piria en la localidad de Punta Lara, Ensenada.";

$texto2 = "En <b>GASAV</b>, nos encargamos de brindarte un servicio completo de guardería para tu equipo deportivo.".PHP_EOL.
"Contamos con cunas para tablas y ganchos para vela para los amantes del windsurf, lockers para kitesurf, cunas para kayaks y stand up paddle, y lockers pequeños para guardar accesorios de nuestros socios.
Disfrutá de nuestro Salón de Usos Múltiples (SUM). Además, tenemos 2 mangrullos de observación y vigilancia de la zona de navegación, un registro de entradas y salidas, un gomón de rescate y un equipo de radio para comunicarnos con las embarcaciones de vela ligera, clubes vecinos o Prefectura.".PHP_EOL.
"En GASAV, somos uno de los pocos clubes que cuenta con una <u>bajada náutica autorizada</u>.".PHP_EOL.
"Entre la zona de esparcimiento y el río, encontrarás un lugar para preparar tus equipos antes de entrar al agua. También contamos con una cancha de voley y un sector de parrillas para que puedas disfrutar con tu familia y amigos.";
$texto3 = "<b>¡Te esperamos para compartir momentos únicos en nuestro club!</b>";

        $data['caption'] = $texto1;
        $data['photo']   = Request::encodeFile($this->telegram->getDownloadPath() . '/Club01.jpg');	        
        Request::sendPhoto($data);        
        $data['caption'] = $texto2;
        $data['photo']   = Request::encodeFile($this->telegram->getDownloadPath() . '/Club02.jpg');	        
        Request::sendPhoto($data);     

        $data['caption'] = 'Podés compartir el bot mediante este QR o este link'.PHP_EOL.
                          'https://t.me/gasavbot';
        $data['photo']   = Request::encodeFile($this->telegram->getDownloadPath() . '/qrbot.jpg');	        
        Request::sendPhoto($data);     

        $data['text'] = $texto3;

        Request::sendMessage($data);
        // Do nothing
        return Request::emptyResponse();
/*
norden - Estado actual del Pilote Norden
foto   - Snapshot de la WebCam al río
start  - Mostrar el QR para compartir
help   - Ayuda
*/

    }
}