<?php
include_once("../../config/base_datos.php");
include_once("../../controladores/controladorPrestamos.php");

$id = isset($_GET["id"]) ? (int) $_GET["id"] : 0;
$mensaje = "";
$claseAlerta = "alert-info";

if ($id > 0) {
    $resultado = ControladorPrestamos::eliminarPrestamo($conexion, $id);

    if ($resultado == "OK") {
        $mensaje = "✅ Préstamo eliminado correctamente.";
        $claseAlerta = "alert-success";
    } else {
        $mensaje = "⚠️ " . htmlspecialchars($resultado);
        $claseAlerta = "alert-danger";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Eliminar Préstamo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="card shadow-lg border-0">
        <div class="card-header bg-danger text-white d-flex align-items-center">
            <i class="bi bi-trash-fill me-2 fs-4"></i>
            <h4 class="mb-0">Eliminar Préstamo</h4>
        </div>
        <div class="card-body text-center">
            <div class="alert <?= $claseAlerta ?> fs-5" role="alert">
                <?= $mensaje ?>
            </div>
            <a href="listar_prestamos.php" class="btn btn-secondary btn-lg fw-bold mt-3">
                <i class="bi bi-arrow-left-circle"></i> Volver al listado
            </a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
