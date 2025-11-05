<?php
$conexion = new mysqli("localhost", "root", "", "");
$sql = file_get_contents("database/sistema_de_ventas.sql");
$conexion->multi_query($sql);
echo "Base de datos importada correctamente.";
?>
