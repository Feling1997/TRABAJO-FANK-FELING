<?php
include_once(__DIR__ . "/../../config/base_datos.php");
include_once(__DIR__ . "/../../controladores/controladorLibros.php");

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

    $mensaje = ($resultado == "OK") ? "Libro agregado correctamente ✅" : $resultado;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Agregar Libro</title>

<!-- ✅ Bootstrap y estilos -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="../../recursos/css/estilos.css">
<script src="../../recursos/js/funciones.js"></script>
</head>
<body>
<div class="container py-5">
    <div class="card mx-auto" style="max-width: 700px;">
        <div class="card-header text-center">
            <h3 class="mb-0"><i class="bi bi-bookmark-plus"></i> Agregar nuevo libro</h3>
        </div>
        <div class="card-body">

            <?php if ($mensaje != ""): ?>
                <div class="alert alert-<?= $resultado == 'OK' ? 'success' : 'danger' ?> text-center">
                    <?= htmlspecialchars($mensaje) ?>
                </div>
            <?php endif; ?>

            <form method="post" onsubmit="return validarFormularioLibro();" class="needs-validation" novalidate>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Título*</label>
                        <input type="text" class="form-control" name="titulo" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Autor*</label>
                        <input type="text" class="form-control" name="autor" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Año</label>
                        <input type="number" class="form-control" name="anio">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Género</label>
                        <input type="text" class="form-control" name="genero">
                    </div>
                    <div class="col-md-4">
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
                <textarea class="form-control mb-3" name="descripcion" rows="3" placeholder="Opcional..."></textarea>

                <div class="d-flex justify-content-between">
                    <a href="listar_libros.php" class="btn btn-secondary">
                        <i class="bi bi-arrow-left-circle"></i> Volver
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Guardar libro
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
