<?php
include 'conexion.php';

$sql = "SELECT citas.id_cita, clientes.nombre, clientes.apellido, servicios.nombre AS servicio, citas.fecha, citas.hora, citas.estado
        FROM citas
        JOIN clientes ON citas.id_cliente = clientes.id_cliente
        JOIN servicios ON citas.id_servicio = servicios.id_servicio";

$resultado = $conexion->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Citas - Manicura</title>
    <link rel="stylesheet" href="estilo.css?v=2">
</head>
<body>
    <div class="contenedor">
        <h1>Lista de Citas</h1>
        <div class="tabla-scroll">
        <table border="1" cellpadding="8">
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Servicio</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
            <?php while ($fila = $resultado->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $fila['id_cita']; ?></td>
                <td><?php echo $fila['nombre'] . " " . $fila['apellido']; ?></td>
                <td><?php echo $fila['servicio']; ?></td>
                <td><?php echo $fila['fecha']; ?></td>
                <td><?php echo $fila['hora']; ?></td>
                <td><?php echo $fila['estado']; ?></td>
                <td>
                    <a href="editar_cita.php?id=<?php echo $fila['id_cita']; ?>">Editar</a> |
                    <a href="eliminar_cita.php?id=<?php echo $fila['id_cita']; ?>" onclick="return confirm('¿Seguro que quieres eliminar esta cita?');">Eliminar</a>
                </td>
            </tr>
            <?php } ?>
        </table>
        </div>
        <br>
        <a href="agregar_cita.php">Agendar nueva cita</a>
        <br><br>
        <a href="index.php">Volver al inicio</a>
    </div>
</body>
</html>