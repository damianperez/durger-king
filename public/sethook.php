<?php declare(strict_types=1);

$result = \TelegramBot\Request::setWebhook([
    'bot_token' => $_ENV['TELEGRAM_BOT_TOKEN'],
    'url' => $_ENV['REMOTE_PATH'] . '/pruebas',
    'drop_pending_updates' => true,
]);

echo $_ENV['REMOTE_PATH'] . '/pruebas' . PHP_EOL;
echo $result->isOk() ? 'Webhook set successfully!' : 'Webhook set failed!';