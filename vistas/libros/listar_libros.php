<?php
include_once(__DIR__ . "/../../config/base_datos.php");
include_once(__DIR__ . "/../../controladores/controladorLibros.php");

$libros = ControladorLibros::obtenerLibros($conexion);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Libros</title>

    <!-- ✅ Bootstrap y estilos globales -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="../../recursos/css/estilos.css">
</head>
<body>
<div class="container py-5">
    <div class="card shadow-lg">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="mb-0"><i class="bi bi-book-half"></i> Listado de Libros</h3>
            <a href="agregar_libro.php" class="btn btn-light btn-sm fw-bold">
                <i class="bi bi-plus-circle"></i> Agregar nuevo libro
            </a>
        </div>

        <div class="card-body bg-white">
            <?php if ($libros && $libros->num_rows > 0): ?>
                <div class="table-responsive">
                    <table id="tablaLibros" class="table table-hover align-middle text-center">
                        <thead class="table-primary">
                            <tr>
                                <th>ID</th>
                                <th>Título</th>
                                <th>Autor</th>
                                <th>ISBN</th>
                                <th>Editorial</th>
                                <th>Categoría</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php while ($fila = $libros->fetch_assoc()): ?>
                            <tr>
                                <td><?= $fila["id"] ?></td>
                                <td><?= htmlspecialchars($fila["titulo"]) ?></td>
                                <td><?= htmlspecialchars($fila["autor"]) ?></td>
                                <td><?= htmlspecialchars($fila["isbn"]) ?></td>
                                <td><?= htmlspecialchars($fila["editorial"]) ?></td>
                                <td><?= htmlspecialchars($fila["categoria"]) ?></td>
                                <td>
                                    <span class="badge <?= $fila["estado"] === "disponible" ? 'bg-success' : 'bg-secondary' ?>">
                                        <?= ucfirst($fila["estado"]) ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="editar_libro.php?id=<?= $fila["id"] ?>" class="btn btn-warning btn-sm text-white fw-bold" title="Editar">
                                        <i class="bi bi-pencil-square"></i> Editar
                                    </a>
                                    <a href="eliminar_libro.php?id=<?= $fila["id"] ?>" class="btn btn-danger btn-sm fw-bold"
                                       onclick="return confirmarEliminacion();" title="Eliminar">
                                       <i class="bi bi-trash"></i> Eliminar
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info text-center mb-0">
                    <i class="bi bi-info-circle"></i> No hay libros registrados.
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
<script src="../../recursos/js/funciones.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    new DataTable('#tablaLibros', {
        language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json' },
        pageLength: 5,
        order: [[0, 'desc']]
    });
});
</script>
</body>
</html>
