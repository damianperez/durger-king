<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Telegram Mini App</title>
    <!-- SDK Oficial de Telegram -->
    <script src="https://telegram.org"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: var(--tg-theme-bg-color, #ffffff);
            color: var(--tg-theme-text-color, #000000);
            margin: 0;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .container { width: 100%; max-width: 400px; text-align: center; }
        button {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            background-color: var(--tg-theme-button-color, #2481cc);
            color: var(--tg-theme-button-text-color, #ffffff);
        }
        .btn-alt { background-color: #e53935; color: white; }
        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Mi Telegram Mini App</h2>
    <p>Hola, <span id="username">Usuario</span></p>
    
    <input type="text" id="mensajeInput" placeholder="Escribe un mensaje aquí...">
    
    <!-- Método 1: Envío silencioso por API sin cerrar la app -->
    <button onclick="enviarViaFetch()">Enviar por Fetch (Mantener Abierta)</button>
    
    <!-- Método 2: Cierra la app y envía los datos nativos (Solo si se abrió con botón de teclado) -->
    <button class="btn-alt" onclick="enviarViaSendData()">Enviar via sendData (Cerrar App)</button>
</div>

<script>
    // Inicializar el SDK de Telegram
    const tg = window.Telegram.WebApp;
    tg.ready();
    tg.expand(); // Expandir la app a pantalla completa

    // Mostrar el nombre del usuario de Telegram en la interfaz
    if (tg.initDataUnsafe && tg.initDataUnsafe.user) {
        document.getElementById('username').innerText = tg.initDataUnsafe.user.first_name;
    }

    // FLUJO 1: Envío directo a PHP vía Fetch (Mantiene la Web App abierta)
    function enviarViaFetch() {
        const texto = document.getElementById('mensajeInput').value;
        if (!texto) return alert('Escribe algo primero');

        // IMPORTANTE: Cambia 'backend.php' por la URL real de tu servidor si es necesario
        fetch('backend.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Telegram-Init-Data': tg.initData // Enviamos la firma para validar en PHP
            },
            body: JSON.stringify({
                accion: "datos_webapp",
                mensaje: texto
            })
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                alert('¡Mensaje enviado con éxito desde PHP!');
            } else {
                alert('Error en servidor: ' + data.error);
            }
        })
        .catch(err => alert('Error de conexión: ' + err));
    }

    // FLUJO 2: Envío nativo de Telegram (Cierra la app instantáneamente)
    // Nota: Solo funciona si la app se abrió desde un KeyboardButton normal
    function enviarViaSendData() {
        const texto = document.getElementById('mensajeInput').value;
        if (!texto) return alert('Escribe algo primero');

        const datosEstructurados = {
            tipo: "web_app_data_nativo",
            mensaje: texto
        };

        // Envía el string a Telegram y cierra la interfaz
        tg.sendData(JSON.stringify(datosEstructurados));
    }
</script>
</body>
</html>
