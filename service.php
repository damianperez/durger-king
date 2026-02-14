<?php declare(strict_types=1);

use ShahradElahi\DurgerKing\App;
use Utilities\Routing\Response;
use Utilities\Routing\Router;
use Utilities\Routing\Utils\StatusCode;

require_once __DIR__ . '/vendor/autoload.php';

ini_set('display_errors', '1');
error_reporting(E_ALL);
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

Router::resource("{$_ENV['REMOTE_URI']}/public", __DIR__ . '/public');

Router::resource("{$_ENV['REMOTE_URI']}/ElBot", __DIR__ . '/ElBot');

Router::any("{$_ENV['REMOTE_URI']}/telegram", function () {
    echo "Ready to /telegram...";
    Response::send(StatusCode::OK, 'Bot is working...');
    $TgBot = require __DIR__ . '/ElBot/hook.php';
    Response::send(StatusCode::OK, 'Bot is working...');
});
/*
Router::any("{$_ENV['REMOTE_URI']}/telegram", __DIR__ . '/ElBot');

Router::any("{$_ENV['REMOTE_URI']}/telegram", function () {
    (new App())->resolve();

    Response::send(StatusCode::OK, 'Bot is working...');
});
*/
Router::any("{$_ENV['REMOTE_URI']}", function () {
    echo "Ready to serve...";
});
