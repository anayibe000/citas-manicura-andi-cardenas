<?php
// login.php
// La clienta ingresa su teléfono. Si existe en la tabla clientes,
// se guarda su id en la sesión y avanza directo a elegir servicio.
session_start();
require 'conexion.php';

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $telefono = trim($_POST['telefono'] ?? '');

    if ($telefono === '') {
        $mensaje = 'Escribe tu número de teléfono.';
    } else {
        $stmt = $conexion->prepare("SELECT id_cliente, nombre, apellido FROM clientes WHERE telefono = ?");
        $stmt->bind_param('s', $telefono);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows === 1) {
            $cliente = $resultado->fetch_assoc();
            $_SESSION['id_cliente'] = $cliente['id_cliente'];
            $_SESSION['nombre_cliente'] = $cliente['nombre'] . ' ' . $cliente['apellido'];

            header('Location: bienvenida_cliente.php');
            exit;
        } else {
            $mensaje = 'No encontramos ese número. ¿Ya te registraste?';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar sesión - Andi Cárdenas</title>
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
        .tarjeta input[type="tel"] {
            width: 100%;
            padding: 14px;
            margin: 16px 0;
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
        .tarjeta input[type="tel"]::placeholder {
    color: #6d2b3a;
    opacity: 1;
}
        .tarjeta button {
            width: 100%;
            padding: 14px;
            border: none;
            outline: none;
            box-shadow: none;
            appearance: none;
            -webkit-appearance: none;
            border-radius: 30px;
            background: #f9a9b1;
            color: #6d2b3a;
            font-weight: 600;
            font-size: 15px;
            font-family: inherit;
            cursor: pointer;
        }
        .tarjeta button:hover {
            background: #f79aa3;
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
        <h1>Iniciar sesión</h1>

        <form method="post">
            <input type="tel" name="telefono" placeholder="teléfono" maxlength="10"
                   value="<?= htmlspecialchars($_POST['Teléfono'] ?? '') ?>" required>
            <button type="submit">Ingresar</button>
        </form>

        <?php if ($mensaje): ?>
            <div class="mensaje error"><?= htmlspecialchars($mensaje) ?></div>
        <?php endif; ?>

        <p>¿No estás registrada?</p>
        <a class="enlace-secundario" href="registro.php">Registrarse</a>
    </div>
</div>

</body>
</html>