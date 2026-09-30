<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'conexion.php';

$id = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $recomendado_para = $_POST['recomendado_para'];
    $tiempo_estimado_minutos = $_POST['tiempo_estimado_minutos'];
    $duracion_resultado = $_POST['duracion_resultado'];
    $valor = $_POST['valor'];

    $sql = "UPDATE servicios SET nombre = ?, descripcion = ?, recomendado_para = ?, tiempo_estimado_minutos = ?, duracion_resultado = ?, valor = ? WHERE id_servicio = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sssissi", $nombre, $descripcion, $recomendado_para, $tiempo_estimado_minutos, $duracion_resultado, $valor, $id);
    $stmt->execute();

    header("Location: servicios.php");
    exit;
}

$sql_servicio = "SELECT * FROM servicios WHERE id_servicio = ?";
$stmt_servicio = $conexion->prepare($sql_servicio);
$stmt_servicio->bind_param("i", $id);
$stmt_servicio->execute();
$servicio = $stmt_servicio->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar Servicio</title>
   <link rel="stylesheet" href="estilo.css?v=2">
</head>
<body>
    <div class="contenedor">
        <h1>Editar Servicio</h1>

        <form method="POST" action="editar_servicio.php?id=<?php echo $servicio['id_servicio']; ?>">
            <label>Nombre:</label>
            <input type="text" name="nombre" value="<?php echo $servicio['nombre']; ?>" required>

            <label>Descripción:</label>
            <textarea name="descripcion" required><?php echo $servicio['descripcion']; ?></textarea>

            <label>Recomendado para:</label>
            <input type="text" name="recomendado_para" value="<?php echo $servicio['recomendado_para']; ?>" required>

            <label>Tiempo estimado (minutos):</label>
            <input type="number" name="tiempo_estimado_minutos" value="<?php echo $servicio['tiempo_estimado_minutos']; ?>" required>

            <label>Duración del resultado (opcional):</label>
            <input type="text" name="duracion_resultado" value="<?php echo $servicio['duracion_resultado']; ?>">

            <label>Valor:</label>
            <input type="number" name="valor" value="<?php echo $servicio['valor']; ?>" required>

            <button type="submit">Guardar Cambios</button>
        </form>

        <br>
        <a href="servicios.php">Volver a la lista</a>
    </div>
</body>
</html>