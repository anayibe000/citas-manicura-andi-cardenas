<?php
include 'conexion.php';

$id = $_GET['id'];
$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nuevo_estado = $_POST['estado'];

    $sql = "UPDATE citas SET estado = ? WHERE id_cita = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("si", $nuevo_estado, $id);
    $stmt->execute();

    header("Location: citas.php");
    exit;
}

// Traer los datos actuales de esa cita para mostrarlos
$sql_cita = "SELECT citas.id_cita, clientes.nombre, clientes.apellido, servicios.nombre AS servicio, citas.fecha, citas.hora, citas.estado
             FROM citas
             JOIN clientes ON citas.id_cliente = clientes.id_cliente
             JOIN servicios ON citas.id_servicio = servicios.id_servicio
             WHERE citas.id_cita = ?";
$stmt_cita = $conexion->prepare($sql_cita);
$stmt_cita->bind_param("i", $id);
$stmt_cita->execute();
$cita = $stmt_cita->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar Cita</title>
    <link rel="stylesheet" href="estilo.css?v=2">
</head>
<body>
    <div class="contenedor">
        <h1>Editar Cita #<?php echo $cita['id_cita']; ?></h1>

        <p><strong>Cliente:</strong> <?php echo $cita['nombre'] . " " . $cita['apellido']; ?></p>
        <p><strong>Servicio:</strong> <?php echo $cita['servicio']; ?></p>
        <p><strong>Fecha:</strong> <?php echo $cita['fecha']; ?></p>
        <p><strong>Hora:</strong> <?php echo $cita['hora']; ?></p>

        <form method="POST" action="editar_cita.php?id=<?php echo $cita['id_cita']; ?>">
            <label>Nuevo estado:</label>
            <select name="estado" required>
                <option value="pendiente" <?php if ($cita['estado'] == 'pendiente') echo 'selected'; ?>>Pendiente</option>
                <option value="confirmada" <?php if ($cita['estado'] == 'confirmada') echo 'selected'; ?>>Confirmada</option>
                <option value="cancelada" <?php if ($cita['estado'] == 'cancelada') echo 'selected'; ?>>Cancelada</option>
            </select>

            <button type="submit">Guardar Cambios</button>
        </form>

        <br>
        <a href="citas.php">Volver a la lista</a>
    </div>
</body>
</html>