<?php
// bienvenida_cliente.php
// Pantalla de transición después de iniciar sesión o registrarse,
// antes de mostrar la lista de servicios.
session_start();

if (!isset($_SESSION['id_cliente'])) {
    header('Location: login_registro.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bienvenida - Andi Cárdenas</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            display: flex;
            justify-content: center;
            font-family: 'Poppins', 'Segoe UI', Arial, sans-serif;
            background: #f9a9b1;
        }
        .pantalla {
            position: relative;
            width: 100%;
            max-width: 420px;
            min-height: 100vh;
            padding: 24px 20px 40px;
            background:
                linear-gradient(180deg, rgba(255,255,255,0) 30%, rgba(249,169,177,0.95) 70%),
                url('img/fondo.jpg') center top / cover no-repeat;
        }
        .tarjeta {
            margin-top: 45vh;
            padding: 30px 24px;
            background: rgba(255,255,255,0.55);
            border-radius: 22px;
            text-align: center;
        }
        .tarjeta h1 {
            color: #e0687f;
            font-size: 24px;
            margin-top: 0;
        }
        .tarjeta p {
            color: #b96b7e;
        }
        .boton-continuar {
            display: inline-block;
            margin-top: 20px;
            padding: 14px 34px;
            border: none;
            outline: none;
            box-shadow: none;
            border-radius: 30px;
            background: #f9a9b1;
            color: #6d2b3a;
            font-weight: 600;
            font-size: 15px;
            text-decoration: none;
        }
        .boton-continuar:hover {
            background: #f79aa3;
        }
    </style>
</head>
<body>

<div class="pantalla">
    <div class="tarjeta">
        <h1>¡Bienvenida!</h1>
        <p>Nos alegra tenerte aquí. Descubre nuestros servicios y agenda tu cita para consentir tus uñas. 💖</p>

        <a class="boton-continuar" href="servicios_cliente.php">Continuar</a>
    </div>
</div>

</body>
</html>