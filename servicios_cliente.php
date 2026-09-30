<?php
// servicios_cliente.php
// Lista de servicios para la clienta que ya inició sesión o se registró.
// Se muestran de a 3 por pantalla, con botones para pasar de página.
session_start();
require 'conexion.php';

if (!isset($_SESSION['id_cliente'])) {
    header('Location: login_registro.php');
    exit;
}

$porPagina = 3;
$pagina = max(1, (int)($_GET['pagina'] ?? 1));
$inicio = ($pagina - 1) * $porPagina;

$totalFila = $conexion->query("SELECT COUNT(*) AS total FROM servicios WHERE es_retiro = 0")->fetch_assoc();
$totalServicios = (int)$totalFila['total'];
$totalPaginas = max(1, ceil($totalServicios / $porPagina));

$stmt = $conexion->prepare("SELECT id_servicio, nombre, descripcion, recomendado_para, tiempo_estimado_minutos, valor FROM servicios WHERE es_retiro = 0 ORDER BY nombre LIMIT ? OFFSET ?");
$stmt->bind_param('ii', $porPagina, $inicio);
$stmt->execute();
$servicios = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nuestros servicios - Andi Cárdenas</title>
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
                linear-gradient(180deg, rgba(255,255,255,0) 20%, rgba(249,169,177,0.95) 45%),
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
        .saludo {
            text-align: center;
            color: #6d2b3a;
            margin-top: 60px;
        }
        h1 {
            text-align: center;
            color: #e0687f;
            margin-top: 4px;
        }
        .tarjeta-servicio {
            background: rgba(255,255,255,0.85);
            border-radius: 16px;
            padding: 18px 20px;
            margin-bottom: 16px;
        }
        .tarjeta-servicio h3 {
            color: #e0687f;
            margin: 0 0 8px;
        }
        .tarjeta-servicio p {
            margin: 4px 0;
            font-size: 14px;
            color: #6d2b3a;
        }
        .boton-agendar {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 22px;
            border: none;
            outline: none;
            box-shadow: none;
            border-radius: 20px;
            background: #f9a9b1;
            color: #6d2b3a;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
        }
        .boton-agendar:hover {
            background: #f79aa3;
        }
        .paginacion {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
        }
        .paginacion a, .paginacion span {
            padding: 10px 20px;
            border-radius: 20px;
            background: rgba(255,255,255,0.85);
            color: #6d2b3a;
            text-decoration: none;
            font-weight: 600;
            font-size: 13px;
        }
        .paginacion .deshabilitado {
            opacity: 0.4;
        }
        .paginacion .info-pagina {
            color: #6d2b3a;
            background: none;
            font-weight: 600;
        }
    </style>
</head>
<body>

<div class="pantalla">
    <a class="volver" href="bienvenida_cliente.php" aria-label="Volver">&larr;</a>

    <p class="saludo">Hola, <?= htmlspecialchars($_SESSION['nombre_cliente']) ?> </p>
    <h1>Nuestros servicios</h1>

    <?php while ($s = $servicios->fetch_assoc()): ?>
        <div class="tarjeta-servicio">
            <h3><?= htmlspecialchars($s['nombre']) ?></h3>
            <p><?= htmlspecialchars($s['descripcion']) ?></p>
            <p><strong>Tiempo estimado:</strong> <?= $s['tiempo_estimado_minutos'] ?> min</p>
            <p><strong>Valor:</strong> $<?= number_format($s['valor'], 0, ',', '.') ?></p>
            <a class="boton-agendar" href="reservar_cita_cliente.php?id_servicio=<?= $s['id_servicio'] ?>">Agendar cita</a>
        </div>
    <?php endwhile; ?>

    <div class="paginacion">
        <?php if ($pagina > 1): ?>
            <a href="?pagina=<?= $pagina - 1 ?>">&larr; Anterior</a>
        <?php else: ?>
            <span class="deshabilitado">&larr; Anterior</span>
        <?php endif; ?>

        <span class="info-pagina">Página <?= $pagina ?> de <?= $totalPaginas ?></span>

        <?php if ($pagina < $totalPaginas): ?>
            <a href="?pagina=<?= $pagina + 1 ?>">Siguiente &rarr;</a>
        <?php else: ?>
            <span class="deshabilitado">Siguiente &rarr;</span>
        <?php endif; ?>
    </div>
</div>

</body>
</html>