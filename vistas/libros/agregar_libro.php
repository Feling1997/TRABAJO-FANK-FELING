<?php
include_once("../config/base_datos.php");
include_once("../controladorLibros.php");

$mensaje = "";
$resultado = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $resultado = ControladorLibros::agregarLibro(
        $conexion,
        $_POST["titulo"],
        $_POST["autor"],
        $_POST["anio"],
        $_POST["genero"],
        $_POST["stock"],
        $_POST["isbn"],
        $_POST["editorial"],
        $_POST["categoria"],
        $_POST["descripcion"]
    );
    if ($resultado == "OK") {
        $mensaje = "Libro agregado correctamente ✅";
    } else {
        $mensaje = $resultado;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Agregar Libro</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../recursos/js/funciones.js"></script>
</head>
<body class="bg-light">
<div class="container mt-4">
    <h2 class="mb-3 text-primary">➕ Agregar Libro</h2>

    <?php if ($mensaje != ""): ?>
    <div id="mensaje" class="alert <?= $resultado == 'OK' ? 'alert-success' : 'alert-danger' ?>">
        <?= $mensaje ?>
    </div>
    <?php endif; ?>

    <form method="post" onsubmit="return validarFormularioLibro();" class="p-3 border rounded bg-white shadow-sm">
        <div class="row mb-3">
            <div class="col">
                <label class="form-label">Título*</label>
                <input type="text" class="form-control" name="titulo" required>
            </div>
            <div class="col">
                <label class="form-label">Autor*</label>
                <input type="text" class="form-control" name="autor" required>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col">
                <label class="form-label">Año</label>
                <input type="number" class="form-control" name="anio">
            </div>
            <div class="col">
                <label class="form-label">Género</label>
                <input type="text" class="form-control" name="genero">
            </div>
            <div class="col">
                <label class="form-label">Stock</label>
                <input type="number" class="form-control" name="stock" min="0">
            </div>
        </div>

        <label class="form-label">ISBN*</label>
        <input type="text" class="form-control mb-3" name="isbn" required>

        <label class="form-label">Editorial</label>
        <input type="text" class="form-control mb-3" name="editorial">

        <label class="form-label">Categoría</label>
        <input type="text" class="form-control mb-3" name="categoria">

        <label class="form-label">Descripción</label>
        <textarea class="form-control mb-3" name="descripcion"></textarea>

        <button type="submit" class="btn btn-primary">Guardar libro</button>
        <a href="listar_libros.php" class="btn btn-secondary">Volver</a>
    </form>
</div>
</body>
</html>
