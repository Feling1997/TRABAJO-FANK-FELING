<?php
include_once("../config/base_datos.php");
include_once("../controladores/controladorPrestamos.php");

$id = isset($_GET["id"]) ? (int) $_GET["id"] : 0;
$mensaje = "";

if ($id > 0) {
    $resultado = ControladorPrestamos::eliminarPrestamo($conexion, $id);

    if ($resultado == "OK") {
        $mensaje = "Prestamo eliminado correctamente ✅";
    } else {
        $mensaje = $resultado;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Eliminar Prestamo</title>
</head>
<body>
<h2>🗑️ Eliminar prestamo</h2>
<p><?= $mensaje ?></p>
<a href="listar_prestamos.php">Volver al listado</a>
</body>
</html>