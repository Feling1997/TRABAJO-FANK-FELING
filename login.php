<?php
require_once __DIR__ . '/config/base_datos.php';
session_start();

$error = null;

// Si ya está logueado, redirige al dashboard
if (isset($_SESSION["usuario_id"])) {
  header("Location: dashboard.php");
  exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $usuario  = trim($_POST["usuario"]  ?? '');
  $password = trim($_POST["password"] ?? '');

  if ($usuario !== '' && $password !== '') {
    $stmt = $conexion->prepare("
      SELECT id, usuario, password, rol
      FROM usuarios_sistema
      WHERE usuario = ?
      LIMIT 1
    ");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $res  = $stmt->get_result();
    $user = $res->fetch_assoc();

    if ($user && password_verify($password, $user["password"])) {
      $_SESSION["usuario_id"] = (int)$user["id"];
      $_SESSION["usuario"]    = $user["usuario"];
      $_SESSION["rol"]        = $user["rol"];
      header("Location: dashboard.php");
      exit;
    } else {
      $error = "Usuario o contraseña incorrectos.";
    }
  } else {
    $error = "Completá ambos campos.";
  }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Iniciar sesión - Biblioteca</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background: linear-gradient(135deg,#2b5876,#4e4376); height:100vh; display:flex; align-items:center; justify-content:center; }
    .login-card { background:#fff; border-radius:16px; box-shadow:0 0 20px rgba(0,0,0,.3); width:100%; max-width:380px; padding:2rem; }
    .btn-primary { background:#4e4376; border:none; } 
    .btn-primary:hover{ background:#2b5876; }
  </style>
</head>
<body>
  <form method="POST" class="login-card">
    <h3 class="text-center mb-3">📚 Biblioteca IAES</h3>

    <?php if ($error): ?>
      <div class="alert alert-danger text-center py-2"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="mb-3">
      <label class="form-label">Usuario</label>
      <input type="text" name="usuario" class="form-control" required autofocus>
    </div>

    <div class="mb-3">
      <label class="form-label">Contraseña</label>
      <input type="password" name="password" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-primary w-100">Ingresar</button>
    <p class="text-center mt-3 mb-0 text-muted" style="font-size:.9rem;">© 2025 - Sistema de Biblioteca</p>
  </form>
</body>
</html>
