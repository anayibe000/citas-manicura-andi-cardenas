<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'conexion.php';

$id = $_GET['id'];

$sql = "DELETE FROM clientes WHERE id_cliente = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: clientes.php");
exit;
?>