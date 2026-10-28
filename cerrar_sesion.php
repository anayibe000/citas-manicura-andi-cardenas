<?php
// cerrar_sesion.php
// Cierra la sesión de la clienta y la devuelve a la presentación.
session_start();
session_unset();
session_destroy();
header("Location: presentacion.php");
exit;