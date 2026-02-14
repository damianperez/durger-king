<?php declare(strict_types=1);

//use ShahradElahi\DurgerKing\App;
use Utilities\Routing\Response;
use Utilities\Routing\Router;
use Utilities\Routing\Utils\StatusCode;

require_once __DIR__ . '/vendor/autoload.php';

$config = require __DIR__ . '/ElBot/config.php';

ini_set('display_errors', '1');
error_reporting(E_ALL);
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

Router::resource("{$_ENV['REMOTE_URI']}/public", __DIR__ . '/public');

Router::resource("{$_ENV['REMOTE_URI']}/ElBot", __DIR__ . '/ElBot');



Router::any("{$_ENV['REMOTE_URI']}/telegram", function () {
   try {
        // Create Telegram API object
        $telegram = new Longman\TelegramBot\Telegram($config['api_key'], $config['bot_username']);

        // Enable admin users
        $telegram->enableAdmins($config['admins']);

        // Add commands paths containing your custom commands
        $telegram->addCommandsPaths($config['commands']['paths']);

        // Enable MySQL if required
        $telegram->enableMySql($config['mysql']);
        // Set custom Download and Upload paths
        $telegram->setDownloadPath($config['paths']['download']);
        $telegram->setUploadPath($config['paths']['upload']);

        // Load all command-specific configurations
        // foreach ($config['commands']['configs'] as $command_name => $command_config) {
        //     $telegram->setCommandConfig($command_name, $command_config);
        // }

        // Requests Limiter (tries to prevent reaching Telegram API limits)
        $telegram->enableLimiter($config['limiter']);

        // Handle telegram webhook request
        $telegram->handle();

    } catch (Longman\TelegramBot\Exception\TelegramException $e) {
        // Log telegram errors
        Longman\TelegramBot\TelegramLog::error($e);

        // Uncomment this to output any errors (ONLY FOR DEVELOPMENT!)
        echo $e;
    } catch (Longman\TelegramBot\Exception\TelegramLogException $e) {
        // Uncomment this to output log initialisation errors (ONLY FOR DEVELOPMENT!)
        echo $e;
    }

    Response::send(StatusCode::OK, 'Bot is working...');
});

Router::any("{$_ENV['REMOTE_URI']}", function () {
    echo "Ready to serve...";
});
