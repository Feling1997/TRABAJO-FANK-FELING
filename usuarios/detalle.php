<?php
require_once __DIR__ . '/config/base_datos.php';
session_start();
if (!isset($_SESSION["usuario_id"])) {
  header("Location: ../login.php");
  exit;
}
?>
<?php
$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
if ($id <= 0) { header("Location: index.php"); exit; }

// Datos del usuario
$u = null;
$stmt = $conexion->prepare("SELECT id, nombre, email, tel, dni, direccion, estado FROM usuarios WHERE id = ? LIMIT 1");
if ($stmt) {
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $res = $stmt->get_result();
  $u = $res ? $res->fetch_assoc() : null;
  $stmt->close();
}
if (!$u) { header("Location: index.php"); exit; }

// Préstamos del usuario
$prestamos = [];
$stmt2 = $conexion->prepare("
  SELECT p.id, p.fecha_prestamo, p.fecha_devolucion, p.fecha_dev_real, p.estado, p.observaciones,
         l.titulo AS libro
  FROM prestamos p
  LEFT JOIN libros l ON l.id = p.libro_id
  WHERE p.usuario_id = ?
  ORDER BY p.id DESC
");
if ($stmt2) {
  $stmt2->bind_param("i", $id);
  if ($stmt2->execute()) {
    $res2 = $stmt2->get_result();
    if ($res2) $prestamos = $res2->fetch_all(MYSQLI_ASSOC);
  }
  $stmt2->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>

  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Usuarios</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <title>Detalle de usuario</title>
</head>
<body class="bg-light">
  <div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-3">
      <h1 class="h4 m-0">Usuario #<?= htmlspecialchars($u['id']) ?></h1>
      <div class="d-flex gap-2">
        <a class="btn btn-primary" href="editar.php?id=<?= (int)$u['id'] ?>">Editar</a>
        <a class="btn btn-outline-secondary" href="index.php">Volver</a>
      </div>
    </div>

    <div class="row g-3">
      <div class="col-md-5">
        <div class="card shadow-sm h-100">
          <div class="card-body">
            <h5 class="card-title mb-3"><?= htmlspecialchars($u['nombre']) ?></h5>
            <p class="text-muted mb-1">DNI: <?= htmlspecialchars($u['dni']) ?></p>
            <p class="mb-1">Email: <?= htmlspecialchars($u['email']) ?></p>
            <p class="mb-1">Tel: <?= htmlspecialchars($u['tel']) ?></p>
            <p class="mb-1">Dirección: <?= htmlspecialchars($u['direccion']) ?></p>
            <p class="mb-0">Estado:
              <?php if ((string)$u['estado']==='1' || $u['estado']==='activo' || $u['estado']==='ACTIVO'): ?>
                <span class="badge text-bg-success">Activo</span>
              <?php else: ?>
                <span class="badge text-bg-warning text-dark">Inactivo</span>
              <?php endif; ?>
            </p>
          </div>
        </div>
      </div>

      <div class="col-md-7">
        <div class="card shadow-sm h-100">
          <div class="card-body">
            <h5 class="card-title mb-3">Préstamos</h5>
            <?php if (!$prestamos): ?>
              <div class="alert alert-light border">Este usuario no posee préstamos registrados.</div>
            <?php else: ?>
              <div class="table-responsive">
                <table class="table table-sm table-striped align-middle">
                  <thead class="table-light">
                    <tr>
                      <th>ID</th>
                      <th>Libro</th>
                      <th>Prestado</th>
                      <th>Vence</th>
                      <th>Devuelto</th>
                      <th>Estado</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($prestamos as $p): ?>
                      <tr>
                        <td><?= htmlspecialchars($p['id']) ?></td>
                        <td><?= htmlspecialchars($p['libro'] ?? '—') ?></td>
                        <td><?= htmlspecialchars($p['fecha_prestamo'] ?? '—') ?></td>
                        <td><?= htmlspecialchars($p['fecha_devolucion'] ?? '—') ?></td>
                        <td><?= htmlspecialchars($p['fecha_dev_real'] ?? '—') ?></td>
                        <td><?= htmlspecialchars($p['estado'] ?? '—') ?></td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
