<?php
include_once("../../config/base_datos.php");
include_once("../../controladores/controladorLibros.php");

$id = isset($_GET["id"]) ? (int) $_GET["id"] : 0;
$mensaje = "";
$libro = null;

// Obtener datos actuales
if ($id > 0) {
    $stmt = $conexion->prepare("SELECT * FROM libros WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $libro = $stmt->get_result()->fetch_assoc();
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

<!-- ✅ Bootstrap y estilos -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="../../recursos/css/estilos.css">
</head>
<body>
<div class="container py-5">
    <div class="card mx-auto" style="max-width: 700px;">
        <div class="card-header text-center">
            <h3 class="mb-0"><i class="bi bi-pencil-square"></i> Editar libro</h3>
        </div>
        <div class="card-body">

            <?php if ($mensaje != ""): ?>
                <div class="alert alert-<?= $resultado == 'OK' ? 'success' : 'danger' ?> text-center">
                    <?= htmlspecialchars($mensaje) ?>
                </div>
            <?php endif; ?>

            <?php if ($libro): ?>
            <form method="post">
                <input type="hidden" name="id" value="<?= $libro["id"] ?>">

                <div class="mb-3">
                    <label class="form-label">Título*</label>
                    <input type="text" class="form-control" name="titulo" value="<?= htmlspecialchars($libro["titulo"]) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Autor*</label>
                    <input type="text" class="form-control" name="autor" value="<?= htmlspecialchars($libro["autor"]) ?>" required>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Año</label>
                        <input type="number" class="form-control" name="anio" value="<?= htmlspecialchars($libro["anio_publicacion"]) ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Género</label>
                        <input type="text" class="form-control" name="genero" value="<?= htmlspecialchars($libro["genero"]) ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Stock</label>
                        <input type="number" class="form-control" name="stock" value="<?= htmlspecialchars($libro["stock"]) ?>">
                    </div>
                </div>

                <label class="form-label">ISBN*</label>
                <input type="text" class="form-control mb-3" name="isbn" value="<?= htmlspecialchars($libro["isbn"]) ?>" required>

                <label class="form-label">Editorial</label>
                <input type="text" class="form-control mb-3" name="editorial" value="<?= htmlspecialchars($libro["editorial"]) ?>">

                <label class="form-label">Categoría</label>
                <input type="text" class="form-control mb-3" name="categoria" value="<?= htmlspecialchars($libro["categoria"]) ?>">

                <label class="form-label">Descripción</label>
                <textarea class="form-control mb-3" name="descripcion" rows="3"><?= htmlspecialchars($libro["descripcion"]) ?></textarea>

                <label class="form-label">Estado</label>
                <select class="form-select mb-4" name="estado">
                    <option value="disponible" <?= $libro["estado"] == "disponible" ? "selected" : "" ?>>Disponible</option>
                    <option value="prestado" <?= $libro["estado"] == "prestado" ? "selected" : "" ?>>Prestado</option>
                    <option value="inactivo" <?= $libro["estado"] == "inactivo" ? "selected" : "" ?>>Inactivo</option>
                </select>

                <div class="d-flex justify-content-between">
                    <a href="listar_libros.php" class="btn btn-secondary">
                        <i class="bi bi-arrow-left-circle"></i> Volver
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-save"></i> Guardar cambios
                    </button>
                </div>
            </form>
            <?php else: ?>
                <div class="alert alert-warning text-center">No se encontró el libro solicitado.</div>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
