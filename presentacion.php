<?php
// bienvenida.php
// Pantalla de bienvenida inicial (primera pantalla de la app).
// No requiere sesión: cualquier visitante la ve al entrar.
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Andi Cárdenas - Amor y cuidado por tus uñas</title>
    <style>
        :root {
            --rosa: #f9a9b1;
            --titulo: #e0687f;
            --vino: #b96b7e;
        }
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
                linear-gradient(180deg, rgba(255,255,255,0) 40%, rgba(249,169,177,0.95) 78%),
                url('img/fondo.jpg') center top / cover no-repeat;
            display: flex;
            flex-direction: column;
        }

        .logo {
            height: 56px;
        }

        .eslogan {
            margin-top: 40px;
            text-align: center;
            font-style: italic;
            color: var(--titulo);
            font-size: 18px;
        }

        .texto-bienvenida {
            margin-top: auto;
            text-align: center;
        }

        .texto-bienvenida h1 {
            color: var(--titulo);
            font-size: 30px;
            margin-bottom: 6px;
        }

        .texto-bienvenida p {
            color: #7a3b48;
            margin-top: 0;
        }

        .botones {
            margin-top: 30px;
        }

        .boton-inicio {
            display: block;
            width: 100%;
            padding: 16px;
            margin-bottom: 16px;
            background: rgba(185, 107, 126, 0.85);
            color: #fff;
            border: none;
            border-radius: 30px;
            font-weight: 700;
            font-size: 15px;
            letter-spacing: 0.5px;
            text-decoration: none;
            text-align: center;
            text-transform: uppercase;
        }

        .boton-inicio:hover {
            background: var(--vino);
        }
    </style>
</head>
<body>

<div class="pantalla">
    <p class="eslogan" style="margin-top: 220px;">Amor y cuidado por tus uñas</p>

    <div class="texto-bienvenida">
        <h1>¡Hola, bienvenida!</h1>
        <p>¿Qué deseas hacer?</p>
    </div>

    <div class="botones">
        <a class="boton-inicio" href="servicios.php">Conocer servicios</a>
        <a class="boton-inicio" href="login_registro.php">Agendar mi cita</a>
    </div>
</div>

</body>
</html>
