<?php
include_once("../config/base_datos.php");

$totalLibros = $conexion->query("SELECT COUNT(*) AS total FROM libros")->fetch_assoc()["total"];
$totalPrestamos = $conexion->query("SELECT COUNT(*) AS total FROM prestamos WHERE estado='activo'")->fetch_assoc()["total"];
$totalUsuarios = $conexion->query("SELECT COUNT(*) AS total FROM usuarios")->fetch_assoc()["total"];
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Panel de Control</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="text-center text-primary mb-4">📊 Panel de Estadísticas</h2>
    <class="text-center my-4">
        <a href="libros/listar_libros.php" class="btn btn-primary btn.lg m-2">📚 Ver Libros</a>
        <a href="prestamos/listar_prestamos.php" class="btn btn-success btn.lg m-2">📘 Ver Préstamos</a>
        <a href="Pablo/usuarios/index.php" class="btn btn-warning btn.lg m-2">👤 Ver Usuarios</a>

    <div class="row text-center mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-primary">
                <div class="card-body">
                    <h5 class="card-title">Libros Totales</h5>
                    <p class="display-6"><?= $totalLibros ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-success">
                <div class="card-body">
                    <h5 class="card-title">Préstamos Activos</h5>
                    <p class="display-6"><?= $totalPrestamos ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-warning">
                <div class="card-body">
                    <h5 class="card-title">Usuarios Registrados</h5>
                    <p class="display-6"><?= $totalUsuarios ?></p>
                </div>
            </div>
        </div>
    </div>

    <canvas id="grafico"></canvas>
</div>

<script>
function cargarGrafico() {
    const ctx = document.getElementById("grafico");
    const datos = [<?= $totalLibros ?>, <?= $totalPrestamos ?>, <?= $totalUsuarios ?>];

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Libros', 'Préstamos Activos', 'Usuarios'],
            datasets: [{
                label: 'Estadísticas de la Biblioteca',
                data: datos,
                backgroundColor: ['#007bff', '#28a745', '#ffc107']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                title: { display: true, text: 'Resumen General', font: { size: 18 } }
            }
        }
    });
    return; // único return
}
window.onload = cargarGrafico;
</script>
</body>
</html>
