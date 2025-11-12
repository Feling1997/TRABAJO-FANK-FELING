<?php
require_once __DIR__ . '/../../config/base_datos.php';
session_start();
if (!isset($_SESSION["usuario_id"])) { header("Location: ../login.php"); exit; }

$sql = "
  SELECT p.id, l.titulo, u.nombre_completo,
         p.fecha_prestamo, p.fecha_devolucion, p.fecha_dev_real, p.estado,
         CASE
           WHEN p.estado = 'Devuelto'
                AND p.fecha_dev_real IS NOT NULL
                AND p.fecha_dev_real > p.fecha_devolucion
             THEN DATEDIFF(p.fecha_dev_real, p.fecha_devolucion)
           ELSE 0
         END AS dias_atraso
  FROM prestamos p
  INNER JOIN libros l ON p.libro_id = l.id
  INNER JOIN usuarios u ON p.usuario_id = u.id
  ORDER BY p.fecha_prestamo DESC, p.id DESC
";
$res  = $conexion->query($sql);
$rows = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Historial de préstamos</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-3 p-md-4">
<div class="container">

  <div class="d-flex align-items-center justify-content-between mb-3">
    <div>
      <h3 class="m-0">📜 Historial de préstamos</h3>
      <small class="text-muted">Consulta de préstamos devueltos y en curso</small>
    </div>
    <a href="index.php" class="btn btn-outline-secondary btn-sm">⬅ Volver a préstamos</a>
  </div>

  <?php if (empty($rows)): ?>
    <div class="alert alert-info">No hay registros de préstamos.</div>
  <?php else: ?>
    <div class="table-responsive">
      <table class="table table-striped align-middle">
        <thead class="table-light">
        <tr>
          <th>#</th><th>Libro</th><th>Usuario</th>
          <th>Fecha préstamo</th><th>Devolución prevista</th>
          <th>Devuelto real</th><th>Estado</th><th>Atraso</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($rows as $r):
          $estadoBadge = $r['estado'] === 'Devuelto'
            ? '<span class="badge bg-success">Devuelto</span>'
            : '<span class="badge bg-primary">Prestado</span>';
          $atraso = (int)$r['dias_atraso'];
          $atrasoBadge = $atraso > 0
            ? '<span class="badge bg-danger">Atraso ' . $atraso . ' día' . ($atraso>1?'s':'') . '</span>'
            : '<span class="badge bg-secondary">Sin atraso</span>';
        ?>
          <tr>
            <td><?= (int)$r['id'] ?></td>
            <td><?= htmlspecialchars($r['titulo']) ?></td>
            <td><?= htmlspecialchars($r['nombre_completo']) ?></td>
            <td><?= htmlspecialchars($r['fecha_prestamo']) ?></td>
            <td><?= htmlspecialchars($r['fecha_devolucion']) ?></td>
            <td><?= htmlspecialchars($r['fecha_dev_real'] ?? '-') ?></td>
            <td><?= $estadoBadge ?></td>
            <td><?= $atrasoBadge ?></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>
</body>
</html>
