<?php
include_once("../config/base_datos.php");
include_once("../controladores/controladorPrestamos.php");

$id = isset($_GET["id"]) ? (int) $_GET["id"] : 0;
$mensaje = "";

if($_SERVER["REQUEST_METHOD"]=="POST"){
    $resultado=ControladorPrestamos::devolverPrestamo($conexion,$_POST["id"],$_POST["fecha_dev_real"]);
    if($resultado=="OK")
        $mensaje="El prestamo se ha devuelto correctamente ✅";
    else
        $mensaje=$resultado;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Devolución de Prestamo</title>
</head>
<body>
<h2>📤 Devolución de Prestamo</h2>

<?php if($mensaje!=""): ?>
    <p style="color:<?=$resultado=='OK' ? 'green' : 'red' ?>;"><?=$mensaje?></p>
<?php endif; ?>

<form method="post">
    <input type="hidden" name="id" value="<?=$id?>">
    <label>Fecha de devolución real:</label><br>
    <input type="date" name="fecha_dev_real" required><br>
    <button type="submit">Registrar devolución</button>
    <a href="listar_prestamos.php">Volver al listado</a>
</form>
</body>
</html>