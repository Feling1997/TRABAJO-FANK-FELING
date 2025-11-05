<?php
include_once("../config/base_datos.php");
include_once("../controladores/controladorPrestamos.php");

$mensaje = "";

$libros=$conexion->query("SELECT id, titulo FROM libros WHERE estado='disponible'");
$usuarios=$conexion->query("SELECT id, nombre_completo FROM usuarios WHERE estado='activo'");

if($_SERVER["REQUEST_METHOD"]=="POST"){
    $resultado=ControladorPrestamos::agregarPrestamo(
        $conexion,
        $_POST["libro_id"],
        $_POST["usuario_id"],
        $_POST["fecha_prestamo"],
        $_POST["fecha_devolucion"],
        $_POST["observaciones"]
    );

    if($resultado=="OK")
        $mensaje="El prestamo se ha agregado correctamente ✅";
    else
        $mensaje=$resultado;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Prestamo</title>
</head>
<body>
<h2>📥 Regitrar Nuevo Prestamo</h2>

<?php if($mensaje!=""): ?>
    <p style="color:<?=$resultado=='OK' ? 'green' : 'red' ?>;"><?=$mensaje?></p>
<?php endif; ?>

<form method="post">
    <label>Libro:</label><br>
    <select name="libro_id" required>
        <option value="">Seleccione un libro</option>
        <?php while($l=$libros->fetch_assoc()): ?>
            <option value="<?=$l["id"]?>"><?=htmlspecialchars($l["titulo"])?></option>
        <?php endwhile; ?>
    </select>

    <label>Usuario:</label><br>
    <select name="usuario_id" required>
        <option value="">Seleccione un usuario</option>
        <?php while($u=$usuarios->fetch_assoc()): ?>
            <option value="<?=$u["id"]?>"><?=htmlspecialchars($u["nombre_completo"])?></option>
        <?php endwhile; ?>
    </select>

    <label>Fecha de prestamo:</label><br>
    <input type="date" name="fecha_prestamo" required><br>

    <label>Fecha de devolución:</label><br>
    <input type="date" name="fecha_devolucion" required><br>

    <label>Observaciones:</label><br>
    <textarea name="observaciones"></textarea><br>

    <button type="submit">Agregar prestamo</button>
    <a href="listar_prestamos.php">Volver al listado</a>
</form>
</body>
</html>