<?php
require_once __DIR__ . '/../config/base_datos.php';
session_start();
if (!isset($_SESSION["usuario_id"])) {
  header("Location: ../login.php");
  exit;
}
?>
<?php
$errores = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $nombre = trim($_POST["nombre_completo"] ?? '');
  $email = trim($_POST["email"] ?? '');
  $tel = trim($_POST["telefono"] ?? '');
  $dni = trim($_POST["dni"] ?? '');
  $direccion = trim($_POST["direccion"] ?? '');
  $estado = isset($_POST["estado"]) ? (int)$_POST["estado"] : 1;

  if ($nombre === '') $errores[] = "El nombre es obligatorio.";
  if ($email === '') $errores[] = "El email es obligatorio.";
  if ($dni === '') $errores[] = "El DNI es obligatorio.";

  if (!$errores) {
    $stmt = $conexion->prepare("
      INSERT INTO usuarios (nombre_completo, email, telefono, dni, direccion, estado)
      VALUES (?, ?, ?, ?, ?, ?)
    ");
    if ($stmt) {
      $stmt->bind_param("sssssi", $nombre, $email, $tel, $dni, $direccion, $estado);
      $stmt->execute();
      $stmt->close();
      header("Location: index.php?ok=1");
      exit;
    } else {
      $errores[] = "Error preparando la consulta: " . $conexion->error;
    }
  }
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

  <title>Crear usuario</title>
</head>
<body class="bg-light">
  <div class="container py-4">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="card shadow-sm">
          <div class="card-header bg-white">
            <h1 class="h4 m-0">Nuevo usuario</h1>
          </div>
          <div class="card-body">
            <?php if ($errores): ?>
              <div class="alert alert-danger"><ul class="mb-0">
                <?php foreach ($errores as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?>
              </ul></div>
            <?php endif; ?>

            <form method="post" class="row g-3">
              <div class="col-12">
                <label class="form-label">Nombre</label>
                <input name="nombre_completo" class="form-control" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Teléfono</label>
                <input name="telefono" class="form-control">
              </div>
              <div class="col-md-6">
                <label class="form-label">DNI</label>
                <input name="dni" class="form-control" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">Dirección</label>
                <input name="direccion" class="form-control">
              </div>
              <div class="col-md-6">
                <label class="form-label">Estado</label>
                <select name="estado" class="form-select">
                  <option value="1">Activo</option>
                  <option value="0">Inactivo</option>
                </select>
              </div>

              <div class="col-12 d-flex justify-content-end gap-2 mt-2">
                <a class="btn btn-outline-secondary" href="index.php">Cancelar</a>
                <button class="btn btn-primary" type="submit">Guardar</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
