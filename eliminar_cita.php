<?php
include 'conexion.php';

$id = $_GET['id'];

$sql = "DELETE FROM citas WHERE id_cita = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: citas.php");
exit;
?>