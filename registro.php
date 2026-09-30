<?php
// registro.php
// Registro de una clienta nueva: nombre y teléfono.
session_start();
require 'conexion.php';

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombreCompleto = trim($_POST['nombre'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');

    $partes = explode(' ', $nombreCompleto, 2);
    $nombre = $partes[0] ?? '';
    $apellido = $partes[1] ?? '';

    if ($nombreCompleto === '' || $telefono === '') {
        $mensaje = 'Completa tu nombre y tu teléfono.';
    } elseif (!preg_match('/^[0-9]{10}$/', $telefono)) {
        $mensaje = 'El teléfono debe tener 10 dígitos numéricos.';
    } else {
        $stmtCheck = $conexion->prepare("SELECT id_cliente FROM clientes WHERE telefono = ?");
        $stmtCheck->bind_param('s', $telefono);
        $stmtCheck->execute();
        $resultadoCheck = $stmtCheck->get_result();

        if ($resultadoCheck->num_rows > 0) {
            $mensaje = 'Ese teléfono ya está registrado. Intenta iniciar sesión.';
        } else {
            $stmt = $conexion->prepare("INSERT INTO clientes (nombre, apellido, telefono) VALUES (?, ?, ?)");
            $stmt->bind_param('sss', $nombre, $apellido, $telefono);

            if ($stmt->execute()) {
                $_SESSION['id_cliente'] = $conexion->insert_id;
                $_SESSION['nombre_cliente'] = $nombreCompleto;

                header('Location: bienvenida_cliente.php');
                exit;
            } else {
                $mensaje = 'Ocurrió un error al registrar: ' . $conexion->error;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registrarse - Andi Cárdenas</title>
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
        .volver {
            position: absolute;
            top: 24px;
            right: 20px;
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: rgba(255,255,255,0.85);
            color: #e0687f;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            font-weight: 700;
            font-size: 18px;
        }
        .tarjeta {
            margin-top: 45vh;
            padding: 26px 22px;
            background: rgba(255,255,255,0.85);
            border-radius: 22px;
            text-align: center;
        }
        .tarjeta h1 {
            color: #e0687f;
            font-size: 22px;
            margin-top: 0;
        }
        .tarjeta p {
            color: #b96b7e;
        }
        .tarjeta input[type="text"],
        .tarjeta input[type="tel"] {
            width: 100%;
            padding: 14px;
            margin: 10px 0;
            border: none;
            outline: none;
            border-radius: 30px;
            background: #f9a9b1;
            color: #6d2b3a;
            font-weight: 600;
            text-align: center;
            font-size: 15px;
            font-family: inherit;
        }
        .tarjeta input::placeholder {
            color: #6d2b3a;
            opacity: 1;
        }
        .boton-flecha {
            display: block;
            width: 50px;
            height: 50px;
            margin: 20px auto 0;
            border: none;
            outline: none;
            box-shadow: none;
            border-radius: 50%;
            background: #b96b7e;
            color: #fff;
            font-size: 20px;
            cursor: pointer;
        }
        .boton-flecha:hover {
            background: #a25a6d;
        }
        .enlace-secundario {
            display: block;
            margin-top: 16px;
            color: #e0687f;
            font-weight: 600;
        }
    </style>
</head>
<body>

<div class="pantalla">
    <a class="volver" href="login_registro.php" aria-label="Volver">&larr;</a>

    <div class="tarjeta">
        <h1>Registrarse</h1>

        <form method="post">
            <input type="text" name="nombre" placeholder="NOMBRE"
                   value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>" required>
            <input type="tel" name="telefono" placeholder="TELÉFONO" maxlength="10"
                   value="<?= htmlspecialchars($_POST['telefono'] ?? '') ?>" required>
            <button type="submit" class="boton-flecha">&#10132;</button>
        </form>

        <?php if ($mensaje): ?>
            <div class="mensaje error"><?= htmlspecialchars($mensaje) ?></div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>