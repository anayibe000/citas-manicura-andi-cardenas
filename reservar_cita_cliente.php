<?php
// reservar_cita_cliente.php
// La clienta ya inició sesión o se registró (viene de servicios_cliente.php).
// Aquí confirma el retiro (si aplica), elige fecha y hora, y se guarda la cita.
session_start();
require 'conexion.php';

if (!isset($_SESSION['id_cliente'])) {
    header('Location: login_registro.php');
    exit;
}

$id_cliente = $_SESSION['id_cliente'];
$mensaje = '';
$claseMensaje = '';

$id_servicio = (int)($_GET['id_servicio'] ?? $_POST['id_servicio'] ?? 0);

$stmtServicio = $conexion->prepare("SELECT id_servicio, nombre, tiempo_estimado_minutos, valor FROM servicios WHERE id_servicio = ? AND es_retiro = 0");
$stmtServicio->bind_param('i', $id_servicio);
$stmtServicio->execute();
$servicio = $stmtServicio->get_result()->fetch_assoc();

if (!$servicio) {
    header('Location: servicios_cliente.php');
    exit;
}

$retiros = $conexion->query("SELECT id_servicio, nombre, tiempo_estimado_minutos, valor FROM servicios WHERE es_retiro = 1 ORDER BY nombre");
$listaRetiros = [];
while ($fila = $retiros->fetch_assoc()) {
    $listaRetiros[] = $fila;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hace_retiro = $_POST['hace_retiro'] ?? 'no';
    $id_retiro = ($hace_retiro === 'si') ? (int)($_POST['id_retiro'] ?? 0) : null;
    $fecha = $_POST['fecha'] ?? '';
    $hora = $_POST['hora'] ?? '';

    if ($fecha === '' || $hora === '') {
        $mensaje = 'Elige una fecha y una hora.';
        $claseMensaje = 'error';
    } elseif ($hace_retiro === 'si' && !$id_retiro) {
        $mensaje = 'Elige cuál retiro vas a realizar.';
        $claseMensaje = 'error';
    } else {
        $tiempoTotal = (int)$servicio['tiempo_estimado_minutos'];
        $valorTotal = (float)$servicio['valor'];

        if ($id_retiro) {
            $stmtRetiro = $conexion->prepare("SELECT tiempo_estimado_minutos, valor FROM servicios WHERE id_servicio = ? AND es_retiro = 1");
            $stmtRetiro->bind_param('i', $id_retiro);
            $stmtRetiro->execute();
            $datosRetiro = $stmtRetiro->get_result()->fetch_assoc();

            if ($datosRetiro) {
                $tiempoTotal += (int)$datosRetiro['tiempo_estimado_minutos'];
                $valorTotal += (float)$datosRetiro['valor'];
            }
        }

        $stmtInsertar = $conexion->prepare(
            "INSERT INTO citas (id_cliente, id_servicio, id_retiro, fecha, hora, estado, tiempo_total_minutos, valor_total)
             VALUES (?, ?, ?, ?, ?, 'pendiente', ?, ?)"
        );
        $stmtInsertar->bind_param('iiissid', $id_cliente, $id_servicio, $id_retiro, $fecha, $hora, $tiempoTotal, $valorTotal);

        if ($stmtInsertar->execute()) {
            $id_cita_nueva = $conexion->insert_id;
            header('Location: confirmacion_cita.php?id_cita=' . $id_cita_nueva);
            exit;
        } else {
            $mensaje = 'Ocurrió un error al guardar la cita: ' . $conexion->error;
            $claseMensaje = 'error';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reservar cita - Andi Cárdenas</title>
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
        h1 {
            text-align: center;
            color: #e0687f;
            margin-top: 60px;
        }
        .resumen-servicio {
            background: rgba(255,255,255,0.85);
            border-radius: 16px;
            padding: 16px 20px;
            margin-bottom: 20px;
            color: #6d2b3a;
            text-align: center;
        }
        .tarjeta {
            background: rgba(255,255,255,0.85);
            border-radius: 22px;
            padding: 22px 20px;
        }
        .tarjeta p {
            color: #b96b7e;
            font-weight: 600;
        }
        .tarjeta label {
            color: #6d2b3a;
            font-weight: 600;
            display: block;
            margin-top: 14px;
            margin-bottom: 6px;
        }
        .tarjeta select,
        .tarjeta input[type="date"],
        .tarjeta input[type="time"] {
            width: 100%;
            padding: 12px;
            border: none;
            outline: none;
            border-radius: 30px;
            background: #f9a9b1;
            color: #6d2b3a;
            font-weight: 600;
            font-family: inherit;
            font-size: 14px;
        }
        .campo-retiro {
            display: none;
        }
        .campo-retiro.mostrar {
            display: block;
        }
        .resumen-total {
            margin-top: 16px;
            padding: 12px 14px;
            background: #f9a9b1;
            color: #6d2b3a;
            border-radius: 10px;
            font-weight: 700;
            text-align: center;
        }
        .boton-confirmar {
            display: block;
            width: 100%;
            margin-top: 18px;
            padding: 14px;
            border: none;
            outline: none;
            box-shadow: none;
            border-radius: 30px;
            background: #b96b7e;
            color: #fff;
            font-weight: 700;
            font-size: 15px;
            cursor: pointer;
        }
        .boton-confirmar:hover {
            background: #a25a6d;
        }
    </style>
</head>
<body>

<div class="pantalla">
    <a class="volver" href="servicios_cliente.php" aria-label="Volver">&larr;</a>

    <h1>Reservar cita</h1>

    <div class="resumen-servicio">
        <strong><?= htmlspecialchars($servicio['nombre']) ?></strong><br>
        <?= $servicio['tiempo_estimado_minutos'] ?> min — $<?= number_format($servicio['valor'], 0, ',', '.') ?>
    </div>

    <div class="tarjeta">
        <form method="post">
            <input type="hidden" name="id_servicio" value="<?= $servicio['id_servicio'] ?>">

            <p>¿Debo realizar algún retiro?</p>
            <label>
                <input type="radio" name="hace_retiro" value="si" <?= (($_POST['hace_retiro'] ?? '') === 'si') ? 'checked' : '' ?>>
                Sí
            </label>
            <label style="display:inline-block; margin-left:16px;">
                <input type="radio" name="hace_retiro" value="no" <?= (($_POST['hace_retiro'] ?? 'no') === 'no') ? 'checked' : '' ?>>
                No
            </label>

            <div class="campo-retiro" id="campoRetiro">
                <label for="id_retiro">Tipo de retiro:</label>
                <select name="id_retiro" id="id_retiro">
                    <option value="">-- Selecciona el retiro --</option>
                    <?php foreach ($listaRetiros as $r): ?>
                        <option value="<?= $r['id_servicio'] ?>"
                                data-tiempo="<?= $r['tiempo_estimado_minutos'] ?>"
                                data-valor="<?= $r['valor'] ?>"
                                <?= (($_POST['id_retiro'] ?? '') == $r['id_servicio']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($r['nombre']) ?> (<?= $r['tiempo_estimado_minutos'] ?> min - $<?= number_format($r['valor'], 0, ',', '.') ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <label for="fecha">Fecha:</label>
            <input type="date" name="fecha" id="fecha" value="<?= htmlspecialchars($_POST['fecha'] ?? '') ?>" required>

            <label for="hora">Hora:</label>
            <input type="time" name="hora" id="hora" value="<?= htmlspecialchars($_POST['hora'] ?? '') ?>" required>

            <div id="resumenTotal" class="resumen-total" style="display:none;"></div>

            <button type="submit" class="boton-confirmar">Confirmar cita</button>

            <?php if ($mensaje): ?>
                <div class="mensaje <?= $claseMensaje ?>"><?= htmlspecialchars($mensaje) ?></div>
            <?php endif; ?>
        </form>
    </div>
</div>

<script>
const tiempoServicioBase = <?= (int)$servicio['tiempo_estimado_minutos'] ?>;
const valorServicioBase = <?= (float)$servicio['valor'] ?>;

const radiosRetiro = document.querySelectorAll('input[name="hace_retiro"]');
const campoRetiro = document.getElementById('campoRetiro');
const selectRetiro = document.getElementById('id_retiro');

function actualizarVisibilidadRetiro() {
    const marcado = document.querySelector('input[name="hace_retiro"]:checked');
    if (marcado && marcado.value === 'si') {
        campoRetiro.classList.add('mostrar');
    } else {
        campoRetiro.classList.remove('mostrar');
        selectRetiro.value = '';
    }
    calcularTotal();
}

radiosRetiro.forEach(r => r.addEventListener('change', actualizarVisibilidadRetiro));

function calcularTotal() {
    let tiempo = tiempoServicioBase;
    let valor = valorServicioBase;

    const haceRetiro = document.querySelector('input[name="hace_retiro"]:checked');
    if (haceRetiro && haceRetiro.value === 'si') {
        const opcionRetiro = selectRetiro.options[selectRetiro.selectedIndex];
        if (opcionRetiro && opcionRetiro.value !== '') {
            tiempo += parseInt(opcionRetiro.dataset.tiempo || 0);
            valor += parseFloat(opcionRetiro.dataset.valor || 0);
        }
    }

    const resumen = document.getElementById('resumenTotal');
    resumen.style.display = 'block';
    resumen.textContent = `Tiempo total: ${tiempo} min — Valor total: $${valor.toLocaleString('es-CO')}`;
}

selectRetiro.addEventListener('change', calcularTotal);

actualizarVisibilidadRetiro();
</script>

</body>
</html>