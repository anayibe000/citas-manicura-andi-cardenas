<?php
// agregar_cita.php
// Formulario para agendar una cita: cliente + servicio principal + retiro opcional.
// Calcula tiempo y valor total sumando servicio + retiro, y lo guarda en la cita.

require 'conexion.php';

$mensaje = '';
$claseMensaje = '';

// --- Traer clientes para la lista desplegable ---
$clientes = $conexion->query("SELECT id_cliente, nombre, apellido FROM clientes ORDER BY nombre");

// --- Traer solo los servicios PRINCIPALES (es_retiro = 0) ---
$servicios = $conexion->query("SELECT id_servicio, nombre, tiempo_estimado_minutos, valor FROM servicios WHERE es_retiro = 0 ORDER BY nombre");

// --- Traer solo los RETIROS (es_retiro = 1) ---
$retiros = $conexion->query("SELECT id_servicio, nombre, tiempo_estimado_minutos, valor FROM servicios WHERE es_retiro = 1 ORDER BY nombre");

// Volvemos a leer los retiros en un arreglo para poder recorrerlos dos veces
// (una para el <select> y otra si hace falta), y lo mismo con servicios.
$listaServicios = [];
while ($fila = $servicios->fetch_assoc()) {
    $listaServicios[] = $fila;
}

$listaRetiros = [];
while ($fila = $retiros->fetch_assoc()) {
    $listaRetiros[] = $fila;
}

// --- Si se envió el formulario ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_cliente = (int)($_POST['id_cliente'] ?? 0);
    $id_servicio = (int)($_POST['id_servicio'] ?? 0);
    $hace_retiro = $_POST['hace_retiro'] ?? 'no';
    $id_retiro = ($hace_retiro === 'si') ? (int)($_POST['id_retiro'] ?? 0) : null;
    $fecha = $_POST['fecha'] ?? '';
    $hora = $_POST['hora'] ?? '';

    if ($id_cliente <= 0 || $id_servicio <= 0 || $fecha === '' || $hora === '') {
        $mensaje = 'Por favor completa todos los campos obligatorios.';
        $claseMensaje = 'error';
    } elseif ($hace_retiro === 'si' && $id_retiro <= 0) {
        $mensaje = 'Elige cuál retiro vas a realizar.';
        $claseMensaje = 'error';
    } else {
        // Buscar tiempo y valor del servicio principal
        $stmt = $conexion->prepare("SELECT tiempo_estimado_minutos, valor FROM servicios WHERE id_servicio = ?");
        $stmt->bind_param('i', $id_servicio);
        $stmt->execute();
        $datosServicio = $stmt->get_result()->fetch_assoc();

        $tiempoTotal = $datosServicio ? (int)$datosServicio['tiempo_estimado_minutos'] : 0;
        $valorTotal = $datosServicio ? (float)$datosServicio['valor'] : 0;

        // Si hay retiro, sumamos su tiempo y valor
        if ($id_retiro) {
            $stmt2 = $conexion->prepare("SELECT tiempo_estimado_minutos, valor FROM servicios WHERE id_servicio = ?");
            $stmt2->bind_param('i', $id_retiro);
            $stmt2->execute();
            $datosRetiro = $stmt2->get_result()->fetch_assoc();

            if ($datosRetiro) {
                $tiempoTotal += (int)$datosRetiro['tiempo_estimado_minutos'];
                $valorTotal += (float)$datosRetiro['valor'];
            }
        }

        // Insertar la cita
        $stmt3 = $conexion->prepare(
            "INSERT INTO citas (id_cliente, id_servicio, id_retiro, fecha, hora, estado, tiempo_total_minutos, valor_total)
             VALUES (?, ?, ?, ?, ?, 'pendiente', ?, ?)"
        );
        $stmt3->bind_param('iiissid', $id_cliente, $id_servicio, $id_retiro, $fecha, $hora, $tiempoTotal, $valorTotal);

        if ($stmt3->execute()) {
            $mensaje = "Cita agendada con éxito. Tiempo total: {$tiempoTotal} min. Valor total: $" . number_format($valorTotal, 0, ',', '.');
            $claseMensaje = 'ok';
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
    <title>Agendar cita - Andi Cárdenas</title>
    <link rel="stylesheet" href="estilo.css">
    <style>
        .campo-retiro {
            display: none;
            margin-top: 10px;
        }
        .campo-retiro.mostrar {
            display: block;
        }
        .resumen-total {
            margin-top: 14px;
            padding: 10px 14px;
            background: #fde7e3;
            border-radius: 10px;
            font-weight: 600;
        }
    </style>
</head>
<body>

<h1>Agendar cita</h1>

<form method="post">

    <label for="id_cliente">Clienta:</label>
    <select name="id_cliente" id="id_cliente" required>
        <option value="">-- Selecciona una clienta --</option>
        <?php while ($c = $clientes->fetch_assoc()): ?>
            <option value="<?= $c['id_cliente'] ?>" <?= (($_POST['id_cliente'] ?? '') == $c['id_cliente']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($c['nombre'] . ' ' . $c['apellido']) ?>
            </option>
        <?php endwhile; ?>
    </select>

    <label for="id_servicio">Servicio:</label>
    <select name="id_servicio" id="id_servicio" required>
        <option value="">-- Selecciona un servicio --</option>
        <?php foreach ($listaServicios as $s): ?>
            <option value="<?= $s['id_servicio'] ?>"
                    data-tiempo="<?= $s['tiempo_estimado_minutos'] ?>"
                    data-valor="<?= $s['valor'] ?>"
                    <?= (($_POST['id_servicio'] ?? '') == $s['id_servicio']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($s['nombre']) ?> (<?= $s['tiempo_estimado_minutos'] ?> min - $<?= number_format($s['valor'], 0, ',', '.') ?>)
            </option>
        <?php endforeach; ?>
    </select>

    <p>¿Debo realizar algún retiro?</p>
    <label>
        <input type="radio" name="hace_retiro" value="si" <?= (($_POST['hace_retiro'] ?? '') === 'si') ? 'checked' : '' ?>>
        Sí
    </label>
    <label>
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

    <button type="submit">Confirmar cita</button>

    <?php if ($mensaje): ?>
        <div class="mensaje <?= $claseMensaje ?>"><?= htmlspecialchars($mensaje) ?></div>
    <?php endif; ?>
</form>

<p><a href="citas.php">Volver al listado de citas</a></p>

<script>
// Mostrar/ocultar el select de retiro según la respuesta Sí/No
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

// Calcular y mostrar el tiempo y valor total en tiempo real
const selectServicio = document.getElementById('id_servicio');

function calcularTotal() {
    let tiempo = 0;
    let valor = 0;

    const opcionServicio = selectServicio.options[selectServicio.selectedIndex];
    if (opcionServicio && opcionServicio.value !== '') {
        tiempo += parseInt(opcionServicio.dataset.tiempo || 0);
        valor += parseFloat(opcionServicio.dataset.valor || 0);
    }

    const haceRetiro = document.querySelector('input[name="hace_retiro"]:checked');
    if (haceRetiro && haceRetiro.value === 'si') {
        const opcionRetiro = selectRetiro.options[selectRetiro.selectedIndex];
        if (opcionRetiro && opcionRetiro.value !== '') {
            tiempo += parseInt(opcionRetiro.dataset.tiempo || 0);
            valor += parseFloat(opcionRetiro.dataset.valor || 0);
        }
    }

    const resumen = document.getElementById('resumenTotal');
    if (tiempo > 0 || valor > 0) {
        resumen.style.display = 'block';
        resumen.textContent = `Tiempo total: ${tiempo} min — Valor total: $${valor.toLocaleString('es-CO')}`;
    } else {
        resumen.style.display = 'none';
    }
}

selectServicio.addEventListener('change', calcularTotal);
selectRetiro.addEventListener('change', calcularTotal);

// Estado inicial (por si el formulario se recarga con datos ya elegidos)
actualizarVisibilidadRetiro();
</script>

</body>
</html>