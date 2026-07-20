<?php declare(strict_types=1);

use ShahradElahi\DurgerKing\App;
use Utilities\Routing\Response;
use Utilities\Routing\Router;
use Utilities\Routing\Utils\StatusCode;

require_once __DIR__ . '/vendor/autoload.php';




ini_set('display_errors', '0');
error_reporting(E_ERROR);
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();



Router::resource("{$_ENV['REMOTE_URI']}/public", __DIR__ . '/public');

Router::resource("{$_ENV['REMOTE_URI']}/ElBot", __DIR__ . '/ElBot');


Router::any("{$_ENV['REMOTE_URI']}/telegram", function () {
    (new App())->resolve();

    Response::send(StatusCode::OK, 'Z /telegram Bot is working...');
});

Router::any("{$_ENV['REMOTE_URI']}", function () {
    
    echo "Ready to serve...";
});

(new App())->resolve();
Response::send(StatusCode::OK, 'Z root Bot is working...');
