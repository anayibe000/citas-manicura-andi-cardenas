<?php
include 'conexion.php';

$sql = "SELECT * FROM servicios";
$resultado = $conexion->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Servicios - Manicura</title>
    <link rel="stylesheet" href="estilo.css?v=2">
</head>
<body>
    <div class="contenedor">
        <h1>Lista de Servicios</h1>
        <div class="tabla-scroll">
        <table border="1" cellpadding="8">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Recomendado para</th>
                <th>Tiempo estimado (min)</th>
                <th>Duración del resultado</th>
                <th>Valor</th>
                <th>Acciones</th>
            </tr>
            <?php while ($fila = $resultado->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $fila['id_servicio']; ?></td>
                <td><?php echo $fila['nombre']; ?></td>
                <td><?php echo $fila['descripcion']; ?></td>
                <td><?php echo $fila['recomendado_para']; ?></td>
                <td><?php echo $fila['tiempo_estimado_minutos']; ?></td>
                <td><?php echo $fila['duracion_resultado']; ?></td>
                <td><?php echo $fila['valor']; ?></td>
                <td>
                    <a href="editar_servicio.php?id=<?php echo $fila['id_servicio']; ?>">Editar</a> |
                    <a href="eliminar_servicio.php?id=<?php echo $fila['id_servicio']; ?>" onclick="return confirm('¿Seguro que quieres eliminar este servicio?');">Eliminar</a>
                </td>
            </tr>
            <?php } ?>
        </table>
        </div>
        <br>
        <a href="agregar_servicio.php">Agregar nuevo servicio</a>
        <br><br>
        <a href="index.php">Volver al inicio</a>
    </div>
</body>
</html>