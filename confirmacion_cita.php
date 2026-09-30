<?php
// confirmacion_cita.php
// Muestra el resumen final de la cita recién agendada.
session_start();
require 'conexion.php';

if (!isset($_SESSION['id_cliente'])) {
    header('Location: login_registro.php');
    exit;
}

$id_cita = (int)($_GET['id_cita'] ?? 0);

$stmt = $conexion->prepare(
    "SELECT c.fecha, c.hora, c.tiempo_total_minutos, c.valor_total,
            cl.nombre, cl.apellido, cl.telefono,
            s.nombre AS nombre_servicio,
            r.nombre AS nombre_retiro
     FROM citas c
     JOIN clientes cl ON cl.id_cliente = c.id_cliente
     JOIN servicios s ON s.id_servicio = c.id_servicio
     LEFT JOIN servicios r ON r.id_servicio = c.id_retiro
     WHERE c.id_cita = ? AND c.id_cliente = ?"
);
$stmt->bind_param('ii', $id_cita, $_SESSION['id_cliente']);
$stmt->execute();
$cita = $stmt->get_result()->fetch_assoc();

if (!$cita) {
    header('Location: servicios_cliente.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Confirmación de cita - Andi Cárdenas</title>
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
                linear-gradient(180deg, rgba(255,255,255,0) 15%, rgba(249,169,177,0.95) 35%),
                url('img/fondo.jpg') center top / cover no-repeat;
        }
        h1 {
            text-align: center;
            color: #e0687f;
            margin-top: 60px;
        }
        .tarjeta {
            background: rgba(255,255,255,0.85);
            border-radius: 22px;
            padding: 22px 24px;
        }
        .fila-dato {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid rgba(185,107,126,0.2);
            color: #6d2b3a;
        }
        .fila-dato:last-of-type {
            border-bottom: none;
        }
        .fila-dato span:first-child {
            color: #e0687f;
            font-weight: 600;
        }
        .mensaje-final {
            margin-top: 20px;
            text-align: center;
            font-style: italic;
            color: #b96b7e;
        }
        .boton-menu {
            display: block;
            width: 100%;
            margin-top: 16px;
            padding: 14px;
            border: none;
            outline: none;
            box-shadow: none;
            border-radius: 30px;
            background: #b96b7e;
            color: #fff;
            text-align: center;
            text-decoration: none;
            font-weight: 700;
        }
        .boton-menu:hover {
            background: #a25a6d;
        }
    </style>
</head>
<body>

<div class="pantalla">
    <h1>Confirmación de cita</h1>

    <div class="tarjeta">
        <div class="fila-dato"><span>Servicio</span><span><?= htmlspecialchars($cita['nombre_servicio']) ?></span></div>
        <?php if ($cita['nombre_retiro']): ?>
            <div class="fila-dato"><span>Retiro</span><span><?= htmlspecialchars($cita['nombre_retiro']) ?></span></div>
        <?php endif; ?>
        <div class="fila-dato"><span>Fecha</span><span><?= date('d/m/Y', strtotime($cita['fecha'])) ?></span></div>
        <div class="fila-dato"><span>Hora</span><span><?= date('g:i A', strtotime($cita['hora'])) ?></span></div>
        <div class="fila-dato"><span>Tiempo estimado</span><span><?= $cita['tiempo_total_minutos'] ?> min</span></div>
        <div class="fila-dato"><span>Valor total</span><span>$<?= number_format($cita['valor_total'], 0, ',', '.') ?></span></div>
        <div class="fila-dato"><span>Clienta</span><span><?= htmlspecialchars($cita['nombre'] . ' ' . $cita['apellido']) ?></span></div>
        <div class="fila-dato"><span>Teléfono</span><span><?= htmlspecialchars($cita['telefono']) ?></span></div>

        <p class="mensaje-final">¡Nos vemos pronto!</p>

        <a class="boton-menu" href="servicios_cliente.php">Agendar otra cita</a>
    </div>
</div>

</body>
</html>