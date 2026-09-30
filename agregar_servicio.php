<?php
include 'conexion.php';

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $recomendado_para = $_POST['recomendado_para'];
    $tiempo_estimado_minutos = $_POST['tiempo_estimado_minutos'];
    $duracion_resultado = $_POST['duracion_resultado'];
    $valor = $_POST['valor'];

    $sql_check = "SELECT * FROM servicios WHERE nombre = ?";
    $stmt_check = $conexion->prepare($sql_check);
    $stmt_check->bind_param("s", $nombre);
    $stmt_check->execute();
    $resultado_check = $stmt_check->get_result();

    if ($resultado_check->num_rows > 0) {
        $mensaje = "Ya existe un servicio con ese nombre. No se agregó de nuevo.";
    } else {
        $sql = "INSERT INTO servicios (nombre, descripcion, recomendado_para, tiempo_estimado_minutos, duracion_resultado, valor) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("sssiss", $nombre, $descripcion, $recomendado_para, $tiempo_estimado_minutos, $duracion_resultado, $valor);
        $stmt->execute();
        $mensaje = "Servicio agregado correctamente.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Agregar Servicio</title>
   <link rel="stylesheet" href="estilo.css?v=2">
</head>
<body>
    <div class="contenedor">
        <h1>Agregar Servicio Nuevo</h1>

        <?php if ($mensaje != "") { ?>
            <p><strong><?php echo $mensaje; ?></strong> <a href="servicios.php">Ver lista de servicios</a></p>
        <?php } ?>

        <form method="POST" action="agregar_servicio.php">
            <label>Nombre:</label>
            <input type="text" name="nombre" required>

            <label>Descripción:</label>
            <textarea name="descripcion" required></textarea>

            <label>Recomendado para:</label>
            <input type="text" name="recomendado_para" required>

            <label>Tiempo estimado (minutos):</label>
            <input type="number" name="tiempo_estimado_minutos" required>

            <label>Duración del resultado (opcional):</label>
            <input type="text" name="duracion_resultado">

            <label>Valor:</label>
            <input type="number" name="valor" required>

            <button type="submit">Guardar Servicio</button>
        </form>

        <br>
        <a href="index.php">Volver al inicio</a>
    </div>
</body>
</html>