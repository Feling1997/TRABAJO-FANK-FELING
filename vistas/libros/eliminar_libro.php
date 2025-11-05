<?php
include_once("../config/base_datos.php");
include_once("../controladorLibros.php");

$id = isset($_GET["id"]) ? (int) $_GET["id"] : 0;
$mensaje = "";

if ($id > 0) {
    $resultado = ControladorLibros::eliminarLibro($conexion, $id);

    if ($resultado == "OK") {
        $mensaje = "Libro eliminado correctamente ✅";
    } else {
        $mensaje = $resultado;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Eliminar Libro</title>
</head>
<body>
<h2>🗑️ Eliminar libro</h2>
<p><?= $mensaje ?></p>
<a href="listar_libros.php">Volver al listado</a>
</body>
</html>
