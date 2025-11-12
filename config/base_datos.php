<?php
$host="localhost";
$usuario="root";
$contrasena="";
$base_datos="biblioteca_fank_feling";

$conexion=new mysqli($host,$usuario,$contrasena,$base_datos);

if($conexion->connect_error){
    die("Falló la conexión con la base de datos: ".$conexion->connect_error);
}
?>