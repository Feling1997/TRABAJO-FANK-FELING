<?php
require_once __DIR__ . '/../../config/base_datos.php';
session_start();
if (!isset($_SESSION["usuario_id"])) { header("Location: ../login.php"); exit; }

$filter = $_GET['filter'] ?? 'todos';
$where  = "p.estado = 'Prestado'";
if ($filter === 'vencidos') {
  $where .= " AND p.fecha_devolucion < CURDATE()";
} elseif ($filter === 'proximos') {
  $where .= " AND p.fecha_devolucion BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 DAY)";
}

$sql = "
  SELECT p.id, l.titulo, u.nombre_completo,
         p.fecha_prestamo, p.fecha_devolucion,
         DATEDIFF(p.fecha_devolucion, CURDATE()) AS dias_restantes
  FROM prestamos p
  INNER JOIN libros l ON p.libro_id = l.id
  INNER JOIN usuarios u ON p.usuario_id = u.id
  WHERE $where
  ORDER BY p.fecha_devolucion ASC, p.fecha_prestamo ASC
";
$res  = $conexion->query($sql);
$rows = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];

$alert = null;
if (!empty($_GET['ok']))    $alert = ['type'=>'success','msg'=>'Operación realizada correctamente.'];
if (!empty($_GET['error'])) $alert = ['type'=>'danger','msg'=>htmlspecialchars($_GET['error'])];
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Préstamos activos</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-3 p-md-4">
<div class="container">

  <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-3">
    <div class="d-flex flex-column">
      <h3 class="m-0">📚 Préstamos activos</h3>
      <div class="small text-muted">Gestioná los préstamos activos o registrá devoluciones</div>
    </div>
    <div class="d-flex align-items-center gap-2 mt-2 mt-md-0">
      <form class="d-flex" method="get">
        <select name="filter" class="form-select form-select-sm me-2" onchange="this.form.submit()">
          <option value="todos"    <?= $filter==='todos'?'selected':''; ?>>Todos</option>
          <option value="vencidos" <?= $filter==='vencidos'?'selected':''; ?>>Vencidos</option>
          <option value="proximos" <?= $filter==='proximos'?'selected':''; ?>>Próximos a vencer (≤3 días)</option>
        </select>
      </form>
      <a href="nuevo_prestamo.php" class="btn btn-sm btn-primary" title="Nuevo préstamo">➕ Nuevo</a>
      <a href="devolver_prestamo.php" class="btn btn-sm btn-success" title="Registrar devolución">📗 Devoluciones</a>
      <a href="historial.php" class="btn btn-sm btn-secondary" title="Ver historial">📜 Historial</a>
    </div>
  </div>

  <?php if ($alert): ?>
    <div class="alert alert-<?= $alert['type'] ?>"><?= $alert['msg'] ?></div>
  <?php endif; ?>

  <?php if (empty($rows)): ?>
    <div class="alert alert-info">No hay préstamos para el filtro seleccionado.</div>
  <?php else: ?>
    <div class="table-responsive">
      <table id="tabla" class="table table-striped align-middle">
        <thead class="table-light">
        <tr>
          <th>#</th><th>Libro</th><th>Usuario</th>
          <th>Fecha préstamo</th><th>Devolución prevista</th>
          <th>Estado</th><th style="width:160px;">Acción</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($rows as $r): 
          $badge = '<span class="badge bg-secondary">En tiempo</span>';
          if ($r['dias_restantes'] < 0)      $badge = '<span class="badge bg-danger">Vencido</span>';
          elseif ($r['dias_restantes'] <= 3) $badge = '<span class="badge bg-warning text-dark">Próximo</span>';
        ?>
          <tr>
            <td><?= (int)$r['id'] ?></td>
            <td><?= htmlspecialchars($r['titulo']) ?></td>
            <td><?= htmlspecialchars($r['nombre_completo']) ?></td>
            <td><?= htmlspecialchars($r['fecha_prestamo']) ?></td>
            <td><?= htmlspecialchars($r['fecha_devolucion']) ?></td>
            <td><?= $badge ?></td>
            <td>
              <form method="post" action="devolver.php" class="d-inline">
                <input type="hidden" name="prestamo_id" value="<?= (int)$r['id'] ?>">
                <button class="btn btn-success btn-sm" type="submit">Registrar devolución</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>
</body>
</html>
