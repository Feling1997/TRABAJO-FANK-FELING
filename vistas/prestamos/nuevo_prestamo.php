<?php
include_once("../../config/base_datos.php");
include_once("../../controladores/controladorPrestamos.php");

$mensaje = "";

$libros = $conexion->query("SELECT id, titulo FROM libros WHERE estado='disponible'");
$usuarios = $conexion->query("SELECT id, nombre_completo FROM usuarios WHERE estado='activo'");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $resultado = ControladorPrestamos::agregarPrestamo(
        $conexion,
        $_POST["libro_id"],
        $_POST["usuario_id"],
        $_POST["fecha_prestamo"],
        $_POST["fecha_devolucion"],
        $_POST["observaciones"]
    );

    $mensaje = ($resultado == "OK") ? "El préstamo se ha agregado correctamente ✅" : $resultado;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Nuevo Préstamo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <div class="card mx-auto" style="max-width: 650px;">
    <link rel="stylesheet" href="../../recursos/css/estilos.css">
</head>
<body>
<div class="container py-5">
        <div class="card-header text-center">
            <h3 class="mb-0"><i class="bi bi-journal-plus"></i> Registrar nuevo préstamo</h3>
        </div>
        <div class="card-body">

            <?php if ($mensaje != ""): ?>
                <div class="alert alert-<?= $resultado == 'OK' ? 'success' : 'danger' ?> text-center">
                    <?= htmlspecialchars($mensaje) ?>
                </div>
            <?php endif; ?>

            <form method="post" class="needs-validation" novalidate>
                <div class="mb-3">
                    <label for="libro_id" class="form-label">📖 Libro*</label>
                    <select class="form-select" id="libro_id" name="libro_id" required>
                        <option value="">Seleccione un libro</option>
                        <?php while ($l = $libros->fetch_assoc()): ?>
                            <option value="<?= $l["id"] ?>"><?= htmlspecialchars($l["titulo"]) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="usuario_id" class="form-label">👤 Usuario*</label>
                    <select class="form-select" id="usuario_id" name="usuario_id" required>
                        <option value="">Seleccione un usuario</option>
                        <?php while ($u = $usuarios->fetch_assoc()): ?>
                            <option value="<?= $u["id"] ?>"><?= htmlspecialchars($u["nombre_completo"]) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="fecha_prestamo" class="form-label">📅 Fecha de préstamo*</label>
                        <input type="date" class="form-control" id="fecha_prestamo" name="fecha_prestamo" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="fecha_devolucion" class="form-label">📆 Fecha de devolución*</label>
                        <input type="date" class="form-control" id="fecha_devolucion" name="fecha_devolucion" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="observaciones" class="form-label">📝 Observaciones</label>
                    <textarea class="form-control" id="observaciones" name="observaciones" rows="3" placeholder="Opcional..."></textarea>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="listar_prestamos.php" class="btn btn-secondary">
                        <i class="bi bi-arrow-left-circle"></i> Volver
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Guardar préstamo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
(() => {
  'use strict'
  const forms = document.querySelectorAll('.needs-validation')
  Array.from(forms).forEach(form => {
    form.addEventListener('submit', event => {
      if (!form.checkValidity()) {
        event.preventDefault()
        event.stopPropagation()
      }
      form.classList.add('was-validated')
    }, false)
  })
})()
</script>
</body>
</html>
