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

Router::any("{$_ENV['REMOTE_URI']}/telegram", function () {
    (new App())->resolve();
    Response::send(StatusCode::OK, 'Bot is working...');
});
Router::any("{$_ENV['REMOTE_URI']}/telegramo", function () {
    (new App())->resolve();
    Response::send(StatusCode::OK, 'Bot is working...');
});
Router::any("{$_ENV['REMOTE_URI']}/telegrama", function () {
    /* Con esto al menos no da error, aunque no se si es lo que se espera... */
        
     $result['ok'] = true;
     $result['coin']=15200;   
     $result["invoice_url"] = "https://t.me/$Nv6DVyQXoUjsEwAA-fIZSx-Ohn4";
     $result['message']='Bot is working...en'.$_ENV['REMOTE_URI'];

    header('Content-type: application/json');
    //echo json_encode($result);

    Response::send(StatusCode::OK, $result );
    
});

Router::any("{$_ENV['REMOTE_URI']}", function () {
    echo "Ready to serve...en". $_ENV['REMOTE_URI'] . '  ' . __DIR__;
});