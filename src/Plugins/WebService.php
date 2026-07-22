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

    /**
     * @param WebAppData $webAppData
     * @return \Generator
     */
    public function onWebAppData(WebAppData $webAppData): \Generator
    {
        //die(var_dump($webAppData->getRawData()));
        
        
        die('not working');
        if ($webAppData->getRawData()['method'] == "makeOrder") {
            header('Content-Type: application/json');

            yield Request::sendMessage([
                'chat_id' => $webAppData->getUser()->getId(),
                'parse_mode' => ParseMode::MARKDOWN,
                'text' => "Your order has been placed successfully! 🍟" . "\n\n" .
                    "Your order is: \n`" . $this->parseOrder($webAppData->getRawData()['order_data']) . "`" . "\n" .
                    "Your order will be delivered to you in 30 minutes. 🚚",
            ]);

            Response::send(StatusCode::OK, [
                'description' => "Your order has been placed successfully! 🍟",
            ]);
        }

        if ($webAppData->getRawData()['method'] == "checkInitData") {
            header('Content-Type: application/json');
            Response::send(StatusCode::OK, [
                'description' => "Chequeo ok...",
            ]);
        }

        if ($webAppData->getRawData()['method'] == "sendMessage") {
            header('Content-Type: application/json');

            yield Request::sendMessage([
                'chat_id' => $webAppData->getUser()->getId(),
                'parse_mode' => ParseMode::MARKDOWN,
                'text' => "Hello World!",
                ...(!$webAppData->getRawData()['with_webview'] ? [] : [
                    'reply_markup' => InlineKeyboard::make()->setKeyboard([
                        [
                            InlineKeyboardButton::make('Open WebApp')->setWebApp($_ENV['RESOURCE_PATH']),
                        ]
                    ])
                ])
            ]);

            Response::send(StatusCode::OK, [
                'description' => "Message sent successfully!",
            ]);
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