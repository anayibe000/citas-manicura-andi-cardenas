<?php
// login_registro.php
// Pantalla intermedia: la clienta elige si ya tiene cuenta o es nueva.
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ingresar - Andi Cárdenas</title>
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
            display: flex;
            flex-direction: column;
            
        }
        .pantalla-opciones {
            text-align: center;
            margin-top: 45vh;
        }
        .boton-opcion {
            display: block;
            width: 100%;
            padding: 16px;
            margin-bottom: 16px;
            background: rgba(255, 255, 255, 0.75);
            color: #e0687f;
            border: none;
            border-radius: 30px;
            font-weight: 700;
            font-size: 16px;
            text-decoration: none;
        }
        .boton-opcion:hover {
            background: rgba(255, 255, 255, 0.9);
        }
    </style>
</head>
<body>

<div class="pantalla">
    <div class="pantalla-opciones">
        <a class="boton-opcion" href="login.php">Iniciar sesión</a>
        <a class="boton-opcion" href="registro.php">Registrarse</a>
    </div>
</div>

</body>
</html>