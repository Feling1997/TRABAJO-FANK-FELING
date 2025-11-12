<?php
require_once __DIR__ . '/../../config/base_datos.php';
session_start();
if (!isset($_SESSION["usuario_id"])) {
  header("Location: ../login.php");
  exit;
}

$alert = null;

$hoy        = date('Y-m-d');
$mas14dias  = date('Y-m-d', strtotime('+14 days'));

$libros = [];
$usuarios = [];

$r = $conexion->query("SELECT id, titulo FROM libros WHERE estado = 'Disponible' ORDER BY titulo ASC");
if ($r) { $libros = $r->fetch_all(MYSQLI_ASSOC); }

$r = $conexion->query("SELECT id, nombre_completo, dni FROM usuarios ORDER BY nombre_completo ASC");
if ($r) { $usuarios = $r->fetch_all(MYSQLI_ASSOC); }

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $libro_id         = (int)($_POST["libro_id"] ?? 0);
  $usuario_id       = (int)($_POST["usuario_id"] ?? 0);
  $fecha_prestamo   = trim($_POST["fecha_prestamo"] ?? '');
  $fecha_devolucion = trim($_POST["fecha_devolucion"] ?? '');

  $errores = [];
  if ($libro_id <= 0)               $errores[] = "Seleccioná un libro.";
  if ($usuario_id <= 0)             $errores[] = "Seleccioná un usuario.";
  if ($fecha_prestamo === '')       $errores[] = "La fecha de préstamo es obligatoria.";
  if ($fecha_devolucion === '')     $errores[] = "La fecha de devolución prevista es obligatoria.";
  if ($fecha_prestamo && $fecha_devolucion && $fecha_devolucion < $fecha_prestamo) {
    $errores[] = "La devolución prevista no puede ser anterior al préstamo.";
  }

  if (!$errores) {
    $conexion->begin_transaction();
    try {
      $stmt = $conexion->prepare("SELECT estado FROM libros WHERE id = ? LIMIT 1");
      $stmt->bind_param("i", $libro_id);
      $stmt->execute();
      $res = $stmt->get_result();
      $lib = $res->fetch_assoc();
      if (!$lib || $lib["estado"] !== "Disponible") {
        throw new Exception("El libro ya no está disponible.");
      }

      $estadoPrestamo = "Prestado";
      $stmt = $conexion->prepare("
        INSERT INTO prestamos (libro_id, usuario_id, fecha_prestamo, fecha_devolucion, estado)
        VALUES (?,?,?,?,?)
      ");
      $stmt->bind_param("iisss", $libro_id, $usuario_id, $fecha_prestamo, $fecha_devolucion, $estadoPrestamo);
      $stmt->execute();

      $stmt = $conexion->prepare("UPDATE libros SET estado = 'No disponible' WHERE id = ?");
      $stmt->bind_param("i", $libro_id);
      $stmt->execute();

      $stmt = $conexion->prepare("UPDATE usuarios SET estado = 'Activo' WHERE id = ? AND estado <> 'Activo'");
      $stmt->bind_param("i", $usuario_id);
      $stmt->execute();

      $conexion->commit();
      header("Location: index.php?ok=1");
      exit;
    } catch (Exception $e) {
      $conexion->rollback();
      $alert = ['type'=>'danger', 'msg'=>$e->getMessage()];
    }
  } else {
    $alert = ['type'=>'danger', 'msg'=> implode('<br>', array_map('htmlspecialchars', $errores))];
  }

  if (!$libros) {
    $r = $conexion->query("SELECT id, titulo FROM libros WHERE estado = 'Disponible' ORDER BY titulo ASC");
    if ($r) { $libros = $r->fetch_all(MYSQLI_ASSOC); }
  }
  if (!$usuarios) {
    $r = $conexion->query("SELECT id, nombre_completo, dni FROM usuarios ORDER BY nombre_completo ASC");
    if ($r) { $usuarios = $r->fetch_all(MYSQLI_ASSOC); }
  }
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Nuevo préstamo</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-3 p-md-4">
<div class="container">
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h3 class="m-0">Registrar nuevo préstamo</h3>
    <a class="btn btn-outline-secondary btn-sm" href="index.php">Volver</a>
  </div>

  <?php if ($alert): ?>
    <div class="alert alert-<?= $alert['type'] ?>"><?= $alert['msg'] ?></div>
  <?php endif; ?>

  <form method="post" class="card shadow-sm">
    <div class="card-body">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Libro</label>
          <select name="libro_id" class="form-select" required>
            <option value="">-- Seleccionar libro disponible --</option>
            <?php foreach ($libros as $l): ?>
              <option value="<?= (int)$l['id'] ?>">
                <?= htmlspecialchars($l['titulo']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="col-md-6">
          <label class="form-label">Usuario</label>
          <select name="usuario_id" class="form-select" required>
            <option value="">-- Seleccionar usuario --</option>
            <?php foreach ($usuarios as $u): ?>
              <option value="<?= (int)$u['id'] ?>">
                <?= htmlspecialchars($u['nombre_completo']) ?><?= $u['dni'] ? ' — DNI '.$u['dni'] : '' ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="col-md-6">
          <label class="form-label">Fecha de préstamo</label>
          <input type="date" name="fecha_prestamo" class="form-control" value="<?= $hoy ?>" required>
        </div>

        <div class="col-md-6">
          <label class="form-label">Fecha de devolución prevista</label>
          <input type="date" name="fecha_devolucion" class="form-control" value="<?= $mas14dias ?>" required>
        </div>
      </div>
    </div>

    <div class="card-footer d-flex justify-content-end gap-2">
      <a class="btn btn-outline-secondary" href="index.php">Cancelar</a>
      <button type="submit" class="btn btn-primary">Guardar</button>
    </div>
  </form>
</div>
</body>
</html>
