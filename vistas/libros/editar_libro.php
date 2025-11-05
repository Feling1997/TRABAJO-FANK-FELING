<?php
include_once("../config/base_datos.php");
include_once("../controladorLibros.php");

$id = isset($_GET["id"]) ? (int) $_GET["id"] : 0;
$mensaje = "";
$libro = null;

// Obtener datos actuales del libro
if ($id > 0) {
    $stmt = $conexion->prepare("SELECT * FROM libros WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $libro = $resultado->fetch_assoc();
}

// Guardar cambios
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $resultado = ControladorLibros::editarLibro(
        $conexion,
        $_POST["id"],
        $_POST["titulo"],
        $_POST["autor"],
        $_POST["anio"],
        $_POST["genero"],
        $_POST["stock"],
        $_POST["isbn"],
        $_POST["editorial"],
        $_POST["categoria"],
        $_POST["descripcion"],
        $_POST["estado"]
    );

    if ($resultado == "OK") {
        $mensaje = "Libro actualizado correctamente ✅";
        // recargar datos actualizados
        $stmt = $conexion->prepare("SELECT * FROM libros WHERE id = ?");
        $stmt->bind_param("i", $_POST["id"]);
        $stmt->execute();
        $libro = $stmt->get_result()->fetch_assoc();
    } else {
        $mensaje = $resultado;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Libro</title>
</head>
<body>
<h2>✏️ Editar libro</h2>

<?php if ($mensaje != ""): ?>
    <p style="color: <?= $resultado == 'OK' ? 'green' : 'red' ?>;">
        <?= $mensaje ?>
    </p>
<?php endif; ?>

<?php if ($libro): ?>
<form method="post">
    <input type="hidden" name="id" value="<?= $libro["id"] ?>">

    <label>Título*</label><br>
    <input type="text" name="titulo" value="<?= htmlspecialchars($libro["titulo"]) ?>" required><br>

    <label>Autor*</label><br>
    <input type="text" name="autor" value="<?= htmlspecialchars($libro["autor"]) ?>" required><br>

    <label>Año de publicación</label><br>
    <input type="number" name="anio" value="<?= htmlspecialchars($libro["anio_publicacion"]) ?>"><br>

    <label>Género</label><br>
    <input type="text" name="genero" value="<?= htmlspecialchars($libro["genero"]) ?>"><br>

    <label>Stock</label><br>
    <input type="number" name="stock" value="<?= htmlspecialchars($libro["stock"]) ?>"><br>

    <label>ISBN*</label><br>
    <input type="text" name="isbn" value="<?= htmlspecialchars($libro["isbn"]) ?>" required><br>

    <label>Editorial</label><br>
    <input type="text" name="editorial" value="<?= htmlspecialchars($libro["editorial"]) ?>"><br>

    <label>Categoría</label><br>
    <input type="text" name="categoria" value="<?= htmlspecialchars($libro["categoria"]) ?>"><br>

    <label>Descripción</label><br>
    <textarea name="descripcion"><?= htmlspecialchars($libro["descripcion"]) ?></textarea><br>

    <label>Estado</label><br>
    <select name="estado">
        <option value="disponible" <?= $libro["estado"] == "disponible" ? "selected" : "" ?>>Disponible</option>
        <option value="prestado" <?= $libro["estado"] == "prestado" ? "selected" : "" ?>>Prestado</option>
        <option value="inactivo" <?= $libro["estado"] == "inactivo" ? "selected" : "" ?>>Inactivo</option>
    </select><br><br>

    <button type="submit">Guardar cambios</button>
    <a href="listar_libros.php">Volver</a>
</form>
<?php else: ?>
<p>No se encontró el libro solicitado.</p>
<?php endif; ?>
</body>
</html>
