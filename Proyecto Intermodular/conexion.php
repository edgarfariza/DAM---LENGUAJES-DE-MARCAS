<?php
$host = "fdb1034.awardspace.net";
$user = "4736484_volley";
$pass = "Volley2026";
$db = "4736484_volley";

$conexion = new mysqli($host, $user, $pass, $db);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
?>