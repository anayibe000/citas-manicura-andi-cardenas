<?php
include 'conexion.php';

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_cliente = $_POST['id_cliente'];
    $id_servicio = $_POST['id_servicio'];
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];
    $estado = $_POST['estado'];

    $sql_check = "SELECT * FROM citas WHERE id_cliente = ? AND id_servicio = ? AND fecha = ? AND hora = ?";
    $stmt_check = $conexion->prepare($sql_check);
    $stmt_check->bind_param("iiss", $id_cliente, $id_servicio, $fecha, $hora);
    $stmt_check->execute();
    $resultado_check = $stmt_check->get_result();

    if ($resultado_check->num_rows > 0) {
        $mensaje = "Ya existe una cita identica registrada. No se agrego de nuevo.";
    } else {
        $sql = "INSERT INTO citas (id_cliente, id_servicio, fecha, hora, estado) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("iisss", $id_cliente, $id_servicio, $fecha, $hora, $estado);
        $stmt->execute();
        $mensaje = "Cita agendada correctamente.";
    }
}

$clientes = $conexion->query("SELECT id_cliente, nombre, apellido FROM clientes ORDER BY nombre");
$servicios = $conexion->query("SELECT id_servicio, nombre FROM servicios ORDER BY nombre");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Agendar Cita</title>
   <link rel="stylesheet" href="estilo.css?v=2">
</head>
<body>
    <div class="contenedor">
        <h1>Agendar Cita Nueva</h1>

        <?php if ($mensaje != "") { ?>
            <p><strong><?php echo $mensaje; ?></strong> <a href="citas.php">Ver lista de citas</a></p>
        <?php } ?>

        <form method="POST" action="agregar_cita.php">
            <label>Cliente:</label>
            <select name="id_cliente" required>
                <option value="">-- Selecciona un cliente --</option>
                <?php while ($c = $clientes->fetch_assoc()) { ?>
                    <option value="<?php echo $c['id_cliente']; ?>"><?php echo $c['nombre'] . " " . $c['apellido']; ?></option>
                <?php } ?>
            </select>

            <label>Servicio:</label>
            <select name="id_servicio" required>
                <option value="">-- Selecciona un servicio --</option>
                <?php while ($s = $servicios->fetch_assoc()) { ?>
                    <option value="<?php echo $s['id_servicio']; ?>"><?php echo $s['nombre']; ?></option>
                <?php } ?>
            </select>

            <label>Fecha:</label>
            <input type="date" name="fecha" required>

            <label>Hora:</label>
            <input type="time" name="hora" required>

            <label>Estado:</label>
            <select name="estado" required>
                <option value="pendiente">Pendiente</option>
                <option value="confirmada">Confirmada</option>
                <option value="cancelada">Cancelada</option>
            </select>

            <button type="submit">Agendar Cita</button>
        </form>

        <br>
        <a href="index.php">Volver al inicio</a>
    </div>
</body>
</html>