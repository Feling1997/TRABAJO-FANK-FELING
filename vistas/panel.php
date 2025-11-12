<?php
include_once("./config/base_datos.php");

$total_libros=$conexion->query("SELECT COUNT(*) AS total FROM libros")->fetch_assoc()["total"];
$prestado=$conexion->query("SELECT COUNT(*) AS total FROM prestamos WHERE devuelto=0")->fetch_assoc()["total"];
$disponible=$total_libros-$prestado;
?>

<h1>Panel de Control</h1>
<p>Total de libros: <?php echo $total_libros; ?></p>
<p>Libros prestados: <?php echo $prestado; ?></p>
<p>Libros disponibles: <?php echo $disponible; ?></p>
