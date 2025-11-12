<?php
require_once __DIR__ . '/../../config/base_datos.php';
session_start();
if (!isset($_SESSION["usuario_id"])) {
  header("Location: ../login.php");
  exit;
}

$alert = null;
if (!empty($_GET['ok'])) {
  $alert = ['type'=>'success','msg'=>'Devolución registrada correctamente.'];
} elseif (!empty($_GET['error'])) {
  $alert = ['type'=>'danger','msg'=> htmlspecialchars($_GET['error'])];
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["prestamo_id"])) {
  $prestamo_id = (int)($_POST["prestamo_id"] ?? 0);

  if ($prestamo_id <= 0) {
    header("Location: devolver.php?error=ID de préstamo inválido");
    exit;
  }

  $conexion->begin_transaction();
  try {
    $stmt = $conexion->prepare("
      SELECT p.id, p.libro_id
      FROM prestamos p
      WHERE p.id = ? AND p.estado = 'Prestado'
      LIMIT 1
    ");
    $stmt->bind_param("i", $prestamo_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $prestamo = $res->fetch_assoc();

    if (!$prestamo) {
      throw new Exception("El préstamo no existe o ya fue devuelto.");
    }

    $libro_id = (int)$prestamo['libro_id'];

    $stmt = $conexion->prepare("
      UPDATE prestamos
      SET estado = 'Devuelto', fecha_dev_real = CURDATE()
      WHERE id = ?
    ");
    $stmt->bind_param("i", $prestamo_id);
    $stmt->execute();

    $stmt = $conexion->prepare("
      UPDATE libros
      SET estado = 'Disponible'
      WHERE id = ?
    ");
    $stmt->bind_param("i", $libro_id);
    $stmt->execute();

    $conexion->commit();
    header("Location: devolver.php?ok=1");
    exit;
  } catch (Exception $e) {
    $conexion->rollback();
    header("Location: devolver.php?error=" . urlencode($e->getMessage()));
    exit;
  }
}

$sql = "
  SELECT 
    p.id,
    l.titulo,
    u.nombre_completo,
    p.fecha_prestamo,
    p.fecha_devolucion
  FROM prestamos p
  INNER JOIN libros l   ON p.libro_id = l.id
  INNER JOIN usuarios u ON p.usuario_id = u.id
  WHERE p.estado = 'Prestado'
  ORDER BY p.fecha_prestamo ASC
";
$res = $conexion->query($sql);
$prestamos = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Devoluciones</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-3 p-md-4">
  <div class="container">

    <div class="d-flex align-items-center justify-content-between mb-3">
      <div>
        <h3 class="m-0">📗 Devoluciones</h3>
        <small class="text-muted">Registrar la devolución de préstamos activos</small>
      </div>
      <div class="d-flex gap-2">
        <input type="text" id="q" class="form-control form-control-sm" placeholder="Buscar préstamo..." onkeyup="filtrar()">
        <a href="index.php" class="btn btn-outline-secondary btn-sm">⬅ Volver a préstamos</a>
      </div>
    </div>

    <?php if ($alert): ?>
      <div class="alert alert-<?= $alert['type'] ?>"><?= $alert['msg'] ?></div>
    <?php endif; ?>

    <?php if (empty($prestamos)): ?>
      <div class="alert alert-info">No hay préstamos pendientes de devolución.</div>
    <?php else: ?>
      <div class="table-responsive">
        <table id="tabla" class="table table-striped align-middle">
          <thead class="table-light">
            <tr>
              <th style="width: 80px;">#</th>
              <th>Libro</th>
              <th>Usuario</th>
              <th>Fecha préstamo</th>
              <th>Devolución prevista</th>
              <th style="width: 160px;">Acción</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($prestamos as $p): ?>
              <tr>
                <td><?= (int)$p['id'] ?></td>
                <td><?= htmlspecialchars($p['titulo']) ?></td>
                <td><?= htmlspecialchars($p['nombre_completo']) ?></td>
                <td><?= htmlspecialchars($p['fecha_prestamo']) ?></td>
                <td><?= htmlspecialchars($p['fecha_devolucion']) ?></td>
                <td>
                  <form method="post" class="d-inline">
                    <input type="hidden" name="prestamo_id" value="<?= (int)$p['id'] ?>">
                    <button class="btn btn-success btn-sm" type="submit">
                      Registrar devolución
                    </button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>

  <script>
    function filtrar() {
      const q = document.getElementById('q')?.value.toLowerCase() || '';
      for (const tr of document.querySelectorAll('#tabla tbody tr')) {
        const texto = tr.innerText.toLowerCase();
        tr.style.display = texto.includes(q) ? '' : 'none';
      }
    }
  </script>
</body>
</html>
