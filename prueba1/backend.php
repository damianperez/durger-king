<?php
/**
 * BACKEND ÚNICO DE TELEGRAM MINI APP (PHP)
 * Configura tu TOKEN de bot antes de subirlo.
 */

header('Content-Type: application/json');

// --- 1. CONFIGURACIÓN INICIAL ---
define('BOT_TOKEN', '963731201:AAGphSbD-uU_s93Xx1y6z2b8ULEE9YTJr88'); // <--- REEMPLAZA ESTO

// --- 2. CAPTURA DE DATOS ---
$json_input = file_get_contents('php://input');
$data = json_decode($json_input, true);
$headers = getallheaders();

// Capturar cabecera de validación de la Web App
$init_data_raw = $headers['X-Telegram-Init-Data'] ?? $headers['x-telegram-init-data'] ?? '';

// --- 3. DISCRIMINACIÓN DE FLUJOS ---

// [CASO A]: Petición desde el Frontend de la Web App (Fetch directo)
if (!empty($init_data_raw)) {
    
    // Validar la firma criptográfica de Telegram por seguridad
    if (!validar_init_data($init_data_raw, BOT_TOKEN)) {
        http_response_code(403);
        echo json_encode(['success' => false, 'error' => 'Firma de autenticación de Telegram inválida']);
        exit;
    }

    // Extraer datos del usuario que envió la petición
    parse_str($init_data_raw, $init_data_parsed);
    $user_data = json_decode($init_data_parsed['user'] ?? '{}', true);
    $chat_id = $user_data['id'] ?? null;
    
    $mensaje_usuario = $data['mensaje'] ?? 'Sin mensaje';

    if ($chat_id) {
        // Enviar respuesta al chat usando el bot
        enviar_mensaje_telegram($chat_id, "📩 <b>Nueva acción desde WebApp:</b>\n" . htmlspecialchars($mensaje_usuario));
        echo json_encode(['success' => true, 'info' => 'Procesado vía Fetch con éxito']);
    } else {
        echo json_encode(['success' => false, 'error' => 'No se pudo obtener el Chat ID']);
    }
    exit;
}

// [CASO B]: Actualizaciones del Webhook nativo de Telegram
if (isset($data['update_id'])) {

    // Sub-caso B1: Datos recibidos desde la WebApp vía tg.sendData()
    if (isset($data['message']['web_app_data'])) {
        $chat_id = $data['message']['chat']['id'];
        $web_app_data = $data['message']['web_app_data'];
        
        $datos_js_crudos = $web_app_data['data']; // El String/JSON enviado desde JS
        $boton_origen = $web_app_data['button_text']; // Texto del botón del teclado
        
        $datos_decodificados = json_decode($datos_js_crudos, true);
        $mensaje_final = $datos_decodificados['mensaje'] ?? $datos_js_crudos;

        // Responder al usuario confirmando la recepción
        $texto_respuesta = "⚡ <b>Datos Nativos Recibidos!</b>\n";
        $texto_respuesta .= "Botón presionado: <code>$boton_origen</code>\n";
        $texto_respuesta .= "Mensaje enviado: " . htmlspecialchars($mensaje_final);

        enviar_mensaje_telegram($chat_id, $texto_respuesta);
        exit;
    }

    // Sub-caso B2: Mensaje de texto estándar en el chat de Telegram
    if (isset($data['message']['text'])) {
        $chat_id = $data['message']['chat']['id'];
        $texto_chat = trim($data['message']['text']);

        if ($texto_chat === '/start') {
            enviar_mensaje_telegram($chat_id, "👋 ¡Hola! Bienvenido. Usa el botón del menú inferior para abrir la aplicación web.");
        } else {
            enviar_mensaje_telegram($chat_id, "Has escrito en el chat: " . htmlspecialchars($texto_chat));
        }
        exit;
    }
}

// Fallback por si la ruta no coincide con ningún origen válido
http_response_code(400);
echo json_encode(['success' => false, 'error' => 'Petición no reconocida']);


// --- 4. FUNCIONES AUXILIARES ---

/**
 * Envía un mensaje de texto formateado en HTML al chat indicado
 */
function enviar_mensaje_telegram($chat_id, $texto) {
    $url = "https://telegram.org" . BOT_TOKEN . "/sendMessage";
    $payload = [
        'chat_id' => $chat_id,
        'text' => $texto,
        'parse_mode' => 'HTML'
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_exec($ch);
    curl_close($ch);
}

/**
 * Algoritmo Oficial de Telegram para verificar la autenticidad de initData
 */
function validar_init_data($init_data, $bot_token) {
    parse_str($init_data, $data_arr);
    if (!isset($data_arr['hash'])) return false;
    
    $check_hash = $data_arr['hash'];
    unset($data_arr['hash']);

    // Ordenar los parámetros alfabéticamente
    ksort($data_arr);
    
    $data_check_string = "";
    foreach ($data_arr as $key => $value) {
        $data_check_string .= $key . '=' . $value . "\n";
    }
    $data_check_string = rtrim($data_check_string, "\n");

    // Generar claves SHA256 HMAC según la especificación técnica de Telegram
    $secret_key = hash_hmac('sha256', $bot_token, 'WebAppData', true);
    $hash = hash_hmac('sha256', $data_check_string, $secret_key);

    return hash_equals($hash, $check_hash);
}
?>
