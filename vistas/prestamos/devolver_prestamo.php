<?php
include_once("../../config/base_datos.php");
include_once("../../controladores/controladorPrestamos.php");

$id = isset($_GET["id"]) ? (int) $_GET["id"] : 0;
$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $resultado = ControladorPrestamos::devolverPrestamo($conexion, $_POST["id"], $_POST["fecha_dev_real"]);
    $mensaje = ($resultado == "OK") ? "El préstamo se ha devuelto correctamente ✅" : $resultado;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Devolución de Préstamo</title>

    <!-- ✅ Bootstrap y estilos -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../../recursos/css/estilos.css">
</head>
<body>
<div class="container py-5">
    <div class="card mx-auto" style="max-width: 550px;">
        <div class="card-header text-center">
            <h3 class="mb-0"><i class="bi bi-arrow-return-left"></i> Devolución de préstamo</h3>
        </div>
        <div class="card-body">

            <?php if ($mensaje != ""): ?>
                <div class="alert alert-<?= $resultado == 'OK' ? 'success' : 'danger' ?> text-center">
                    <?= htmlspecialchars($mensaje) ?>
                </div>
            <?php endif; ?>

            <form method="post" class="needs-validation" novalidate>
                <input type="hidden" name="id" value="<?= $id ?>">

                <div class="mb-3">
                    <label for="fecha_dev_real" class="form-label">📆 Fecha de devolución real*</label>
                    <input type="date" class="form-control" id="fecha_dev_real" name="fecha_dev_real" required>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="listar_prestamos.php" class="btn btn-secondary">
                        <i class="bi bi-arrow-left-circle"></i> Volver
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle"></i> Registrar devolución
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
