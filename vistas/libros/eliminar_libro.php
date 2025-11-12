<?php
include_once("../../config/base_datos.php");
include_once("../../controladores/controladorLibros.php");

$id = isset($_GET["id"]) ? (int) $_GET["id"] : 0;
$mensaje = "";
$resultado = "";

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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .card {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: none;
        }
        .card-header {
            background: linear-gradient(90deg, #0d6efd, #198754);
            color: white;
        }
        label { font-weight: 600; margin-bottom: 0.3rem; }
        input, select, textarea { border-radius: 8px !important; }
        textarea { resize: none; }
        .btn { border-radius: 8px; font-weight: 500; }
        .btn i { margin-right: 4px; }
        .alert { font-weight: 500; }
        .table { border-radius: 10px; overflow: hidden; }
        .table thead th { background-color: #e9f0ff; font-weight: 600; }
        .table-hover tbody tr:hover { background-color: #f0f8ff; }
        h2, h3 { font-weight: 700; color: #0d6efd; }
        a { text-decoration: none; }
        a:hover { opacity: 0.85; }
        button, a, input, select, textarea { transition: all 0.2s ease-in-out; }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="card mx-auto" style="max-width: 600px;">
        <div class="card-header text-center">
            <h4 class="mb-0">🗑️ Eliminar libro</h4>
        </div>
        <div class="card-body text-center">
            <?php if ($mensaje != ""): ?>
                <div class="alert <?= ($resultado == 'OK') ? 'alert-success' : 'alert-danger' ?>">
                    <?= $mensaje ?>
                </div>
            <?php else: ?>
                <p class="text-muted">Procesando eliminación...</p>
            <?php endif; ?>

            <div class="mt-4">
                <a href="listar_libros.php" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left-circle"></i> Volver al listado
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap Icons (para los íconos de los botones) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

</body>
</html>
