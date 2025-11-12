<?php
include_once(__DIR__ . "/../../config/base_datos.php");
include_once(__DIR__ . "/../../controladores/controladorPrestamos.php");

$prestamos = ControladorPrestamos::obtenerPrestamos($conexion);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Préstamos</title>

    <!-- ✅ Bootstrap y estilos -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../../recursos/css/estilos.css">

    <!-- ✅ DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
</head>
<body>
<div class="container py-5">
    <div class="card shadow-lg">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="mb-0"><i class="bi bi-journal-check"></i> Listado de Préstamos</h3>
            <a href="nuevo_prestamo.php" class="btn btn-light btn-sm fw-bold">
                <i class="bi bi-plus-circle"></i> Nuevo préstamo
            </a>
        </div>

        <div class="card-body bg-white">
            <?php if ($prestamos && $prestamos->num_rows > 0): ?>
                <div class="table-responsive">
                    <table id="tablaPrestamos" class="table table-hover align-middle text-center">
                        <thead class="table-primary">
                            <tr>
                                <th>ID</th>
                                <th>Libro</th>
                                <th>Usuario</th>
                                <th>Fecha de préstamo</th>
                                <th>Fecha de devolución</th>
                                <th>Observaciones</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($fila = $prestamos->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $fila["id"] ?></td>
                                    <td><?= htmlspecialchars($fila["libro"]) ?></td>
                                    <td><?= htmlspecialchars($fila["usuario"]) ?></td>
                                    <td><?= htmlspecialchars($fila["fecha_prestamo"]) ?></td>
                                    <td><?= htmlspecialchars($fila["fecha_devolucion"]) ?></td>
                                    <td><?= htmlspecialchars($fila["observaciones"]) ?></td>
                                    <td>
                                        <?php if($fila["estado"]=="activo"): ?>
                                            <span class="badge bg-success">Activo</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Devuelto</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($fila["estado"]=="activo"): ?>
                                            <a href="devolver_prestamo.php?id=<?= $fila["id"] ?>" class="btn btn-outline-success btn-sm">
                                                <i class="bi bi-arrow-return-left"></i>
                                            </a>
                                        <?php endif; ?>
                                        <a href="editar_prestamo.php?id=<?= $fila["id"] ?>" class="btn btn-outline-danger btn-sm"
                                           onclick="return confirm('¿Seguro que deseas eliminar este préstamo?');">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info text-center mb-0">
                    <i class="bi bi-info-circle"></i> No hay préstamos registrados.
                </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="text-center mt-4">
        <a href="../panel.php" class="btn btn-secondary btn-lg fw-bold">
            <i class="bi bi-arrow-left-circle"></i> Volver
        </a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    new DataTable('#tablaPrestamos', {
        language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json' },
        pageLength: 5,
        order: [[0, 'desc']]
    });
});
</script>
</body>
</html>
