<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'conexion.php';

$id = $_GET['id'];
$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $telefono = $_POST['telefono'];

    if (!preg_match('/^[0-9]{10}$/', $telefono)) {
        $mensaje = "El teléfono debe tener exactamente 10 dígitos numéricos.";
    } else {
        $sql = "UPDATE clientes SET nombre = ?, apellido = ?, telefono = ? WHERE id_cliente = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("sssi", $nombre, $apellido, $telefono, $id);
        $stmt->execute();

        header("Location: clientes.php");
        exit;
    }
}

$sql_cliente = "SELECT * FROM clientes WHERE id_cliente = ?";
$stmt_cliente = $conexion->prepare($sql_cliente);
$stmt_cliente->bind_param("i", $id);
$stmt_cliente->execute();
$cliente = $stmt_cliente->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar Cliente</title>
   <link rel="stylesheet" href="estilo.css?v=2">
</head>
<body>
    <div class="contenedor">
        <h1>Editar Cliente</h1>

        <?php if ($mensaje != "") { ?>
            <p style="color:red;"><strong><?php echo $mensaje; ?></strong></p>
        <?php } ?>

        <form method="POST" action="editar_cliente.php?id=<?php echo $cliente['id_cliente']; ?>">
            <label>Nombre:</label>
            <input type="text" name="nombre" value="<?php echo $cliente['nombre']; ?>" required>

            <label>Apellido:</label>
            <input type="text" name="apellido" value="<?php echo $cliente['apellido']; ?>" required>

            <label>Teléfono:</label>
            <input type="text" name="telefono" value="<?php echo $cliente['telefono']; ?>" maxlength="10" required>

            <button type="submit">Guardar Cambios</button>
        </form>

        <br>
        <a href="clientes.php">Volver a la lista</a>
    </div>
</body>
</html>