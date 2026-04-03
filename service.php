<?php declare(strict_types=1);

use ShahradElahi\DurgerKing\App;
use Utilities\Routing\Response;
use Utilities\Routing\Router;
use Utilities\Routing\Utils\StatusCode;

require_once __DIR__ . '/vendor/autoload.php';

//ini_set('display_errors', '1');
//error_reporting(E_ALL);
#REMOTE_BASE_PATH="https://bots.perezcompany.com.ar" # Without trailing slash
#REMOTE_URI="/durger-king" # Leave empty if your in the root of your domain (With trailing slash)

#REMOTE_PATH="${REMOTE_BASE_PATH}${REMOTE_URI}" # DO NOT CHANGE THIS
#RESOURCE_PATH="${REMOTE_PATH}/public/" # DO NOT CHANGE THIS

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
Router::resource("{$_ENV['REMOTE_URI']}", __DIR__ . '/public');
Router::resource("{$_ENV['REMOTE_URI']}/public", __DIR__ . '/public');
Router::resource("{$_ENV['REMOTE_URI']}/ElBot", __DIR__ . '/ElBot');

Router::any("{$_ENV['REMOTE_URI']}/telegram", function () {
    (new App())->resolve();
    Response::send(StatusCode::OK, 'Bot is working en el raiz...');
});

Router::any("{$_ENV['REMOTE_URI']}", function () {
    echo "Ready to serve...";
});