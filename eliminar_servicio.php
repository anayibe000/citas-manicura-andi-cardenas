<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'conexion.php';

$id = $_GET['id'];

$sql = "DELETE FROM servicios WHERE id_servicio = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: servicios.php");
exit;
?>