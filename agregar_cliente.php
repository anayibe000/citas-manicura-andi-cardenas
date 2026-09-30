<?php
include 'conexion.php';

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $telefono = $_POST['telefono'];

    if (!preg_match('/^[0-9]{10}$/', $telefono)) {
        $mensaje = "El teléfono debe tener exactamente 10 dígitos numéricos, sin puntos ni letras.";
    } else {
        $sql_check = "SELECT * FROM clientes WHERE nombre = ? AND apellido = ? AND telefono = ?";
        $stmt_check = $conexion->prepare($sql_check);
        $stmt_check->bind_param("sss", $nombre, $apellido, $telefono);
        $stmt_check->execute();
        $resultado_check = $stmt_check->get_result();

        if ($resultado_check->num_rows > 0) {
            $mensaje = "Este cliente ya está registrado. No se agregó de nuevo.";
        } else {
            $sql = "INSERT INTO clientes (nombre, apellido, telefono) VALUES (?, ?, ?)";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("sss", $nombre, $apellido, $telefono);
            $stmt->execute();
            $mensaje = "Cliente agregado correctamente.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Agregar Cliente</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <div class="contenedor">
        <h1>Agregar Cliente Nuevo</h1>

        <?php if ($mensaje != "") { ?>
            <p><strong><?php echo $mensaje; ?></strong> <a href="clientes.php">Ver lista de clientes</a></p>
        <?php } ?>

        <form method="POST" action="agregar_cliente.php">
            <label>Nombre:</label>
            <input type="text" name="nombre" required>

            <label>Apellido:</label>
            <input type="text" name="apellido" required>

            <label>Teléfono (10 dígitos, solo números):</label>
            <input type="text" name="telefono" maxlength="10" pattern="[0-9]{10}" required>

            <button type="submit">Guardar Cliente</button>
        </form>

        <br>
        <a href="index.php">Volver al inicio</a>
    </div>
</body>
</html>