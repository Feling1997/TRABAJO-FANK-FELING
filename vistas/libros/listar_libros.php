<?php
include_once("../config/base_datos.php");
include_once("../controladorLibros.php");

$libros = ControladorLibros::obtenerLibros($conexion);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Listado de Libros</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../recursos/js/funciones.js"></script>
</head>
<body class="bg-light">
<div class="container mt-4">
    <h2 class="mb-3 text-primary">📚 Libros</h2>
    <a href="agregar_libro.php" class="btn btn-success mb-3">➕ Agregar nuevo libro</a>

    <table class="table table-striped table-bordered">
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
                <td><?= htmlspecialchars($fila["estado"]) ?></td>
                <td>
                    <a href="editar_libro.php?id=<?= $fila["id"] ?>" class="btn btn-sm btn-warning">✏️ Editar</a>
                    <a href="eliminar_libro.php?id=<?= $fila["id"] ?>" class="btn btn-sm btn-danger" onclick="return confirmarEliminacion();">🗑️ Eliminar</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
