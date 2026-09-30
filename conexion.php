<?php
$host = "localhost";
$usuario = "root";
$contrasena = "";
$base_datos = "andi_cardenas";

$conexion = new mysqli($host, $usuario, $contrasena, $base_datos);

if ($conexion->connect_error) {
    die("Error de conexion: " . $conexion->connect_error);
}

echo "Conexion exitosa";
?>
