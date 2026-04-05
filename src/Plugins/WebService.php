<?php declare(strict_types=1);

namespace ShahradElahi\DurgerKing\Plugins;

use TelegramBot\Entities\InlineKeyboard;
use TelegramBot\Entities\InlineKeyboardButton;
use TelegramBot\Entities\WebAppData;
use TelegramBot\Enums\ParseMode;
use TelegramBot\Request;
use Utilities\Routing\Response;
use Utilities\Routing\Utils\StatusCode;

/**
 * Class WebService 
 *
 * The Class will handle the requests for the WebApp.
 *
 * @author     Shahrad Elahi <shahrad@litehex.com>
 * @link       https://github.com/telegram-bot-php/durger-king
 * @version    v1.0.0
 */
class WebService extends \TelegramBot\Plugin
{

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


    /**
     * @param WebAppData $webAppData
     * @return \Generator
     * 
     * EL invoice y el WebAppData llegan por separado, no se si es un bug o es asi, pero el invoice llega con el mensaje y 
     * el WebAppData llega despues, 
     * por eso los debug para ver que llega primero
     * Esto deberia devolver el makeOrder     * 
     *  {"ok":true,"invoice_url":"https:\/\/t.me\/$Nv6DVyQXoUjsEwAA-fIZSx-Ohn4"}
     * Luego pasar a la pantalla de checkout. 
     * Despues de pagar, el bot recibe el update con el WebAppData con la info del pedido, y ahi se procesa el pedido.
     * Entonces sí manda el mensaje ""Your order has been placed successfully"
     * 
     *  
     * 
     */    
    public function onWebAppData(WebAppData $webAppData): \Generator
    {
        $this->debug_a_admins('onWebAppData', $webAppData->getRawData());
	    //
        // die(var_dump($webAppData));
        if ($webAppData->getRawData()['method'] == "makeOrder") {
            header('Content-Type: application/json');

	    yield Request::sendMessage([

                'chat_id' => $webAppData->getUser()->getId(),
                'parse_mode' => ParseMode::MARKDOWN,
                'text' => "Your order has been placed successfully! 🍟" . "\n\n" .
                    "Your order is: \n`" . $this->parseOrder($webAppData->getRawData()['order_data']) . "`" . "\n" .
                    "Your order will be delivered to you in 30 minutes. 🚚",
            ]);

            Response::send(StatusCode::OK,[
                        "ok"=> true,
                        "invoice_url" => "https://t.me/$NG4UvISlmUpdIwAAFps900FiGKM"
                    ]);
            //public static function send(int $statusCode, string|array $body = []): void
            
        }

        if ($webAppData->getRawData()['method'] == "checkInitData") {
            header('Content-Type: application/json');
            Response::send(StatusCode::OK);
        }

        if ($webAppData->getRawData()['method'] == "sendMessage") {
            header('Content-Type: application/json');

            yield   Request::sendMessage([
                'chat_id' => $webAppData->getUser()->getId(),
                'parse_mode' => ParseMode::MARKDOWN,
                'text' => "Hello World!",
                ...(!$webAppData->getRawData()['with_webview'] ? [] : [
                    'reply_markup' => InlineKeyboard::make()->setKeyboard([
                        [
                            InlineKeyboardButton::make('Open WebApp')->setWebApp($_ENV['RESOURCE_BASE_URL']),
                        ]
                    ])
                ])
            ]);

            Response::send(StatusCode::OK);
            //Response::send(StatusCode::OK);
        }
    }

    /**
     * @param string $order
     * @return string
     */
    protected function parseOrder(string $order = '[]'): string
    {
        if ($order == '[]') {
            return 'Nothing';
        }

        $order = json_decode($order, true);
        $order_text = '';
        foreach ($order as $item) {
            $order_text .= (
                $item['count'] . 'x ' .
                $this->store_items[$item['id']]['name'] . ' ' .
                $this->store_items[$item['id']]['emoji'] . ' $' .
                ($this->store_items[$item['id']]['price'] * $item['count']) . "\n"
            );
        }
        return $order_text;
    }

    /**
     * The available items in the store.
     *
     * @var array|array[]
     */
    protected array $store_items = [
        1 => [
            'name' => 'Burger',
            'emoji' => '🍔',
            'price' => 5,
        ],
        2 => [
            'name' => 'Fries',
            'emoji' => '🍟',
            'price' => 2,
        ],
        3 => [
            'name' => 'Drink',
            'emoji' => '🥤',
            'price' => 1,
        ],
        4 => [
            'name' => 'Salad',
            'emoji' => '🥗',
            'price' => 3,
        ],
        5 => [
            'name' => 'Pizza',
            'emoji' => '🍕',
            'price' => 4,
        ],
        6 => [
            'name' => 'Sandwich',
            'emoji' => '🥪',
            'price' => 3,
        ],
        7 => [
            'name' => 'Hot Dog',
            'emoji' => '🌭',
            'price' => 2,
        ],
        8 => [
            'name' => 'Ice Cream',
            'emoji' => '🍦',
            'price' => 2,
        ],
        9 => [
            'name' => 'Cake',
            'emoji' => '🍰',
            'price' => 3,
        ],
        10 => [
            'name' => 'Donut',
            'emoji' => '🍩',
            'price' => 1,
        ],
        11 => [
            'name' => 'Cupcake',
            'emoji' => '🧁',
            'price' => 1,
        ],
        12 => [
            'name' => 'Cookie',
            'emoji' => '🍪',
            'price' => 1,
        ],
        13 => [
            'name' => 'Sushi',
            'emoji' => '🍣',
            'price' => 4,
        ],
        14 => [
            'name' => 'Noodles',
            'emoji' => '🍜',
            'price' => 3,
        ],
        15 => [
            'name' => 'Steak',
            'emoji' => '🥩',
            'price' => 5,
        ],
    ];

}
