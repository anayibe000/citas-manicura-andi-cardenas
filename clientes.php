<?php
include 'conexion.php';

$sql = "SELECT * FROM clientes";
$resultado = $conexion->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Clientes - Manicura</title>
    <link rel="stylesheet" href="estilo.css?v=2">
</head>
<body>
    <div class="contenedor">
        <h1>Lista de Clientes</h1>
        <table border="1" cellpadding="8">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Teléfono</th>
                <th>Fecha de registro</th>
                <th>Acciones</th>
            </tr>
            <?php while ($fila = $resultado->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $fila['id_cliente']; ?></td>
                <td><?php echo $fila['nombre']; ?></td>
                <td><?php echo $fila['apellido']; ?></td>
                <td><?php echo $fila['telefono']; ?></td>
                <td><?php echo $fila['fecha_registro']; ?></td>
                <td>
                    <a href="editar_cliente.php?id=<?php echo $fila['id_cliente']; ?>">Editar</a> |
                    <a href="eliminar_cliente.php?id=<?php echo $fila['id_cliente']; ?>" onclick="return confirm('¿Seguro que quieres eliminar este cliente?');">Eliminar</a>
                </td>
            </tr>
            <?php } ?>
        </table>
        <br>
        <a href="agregar_cliente.php">Agregar nuevo cliente</a>
        <br><br>
        <a href="index.php">Volver al inicio</a>
    </div>
</body>
</html>