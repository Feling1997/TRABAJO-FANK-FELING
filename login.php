<?php
session_start();
require "base_datos.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $usuario = trim($_POST["usuario"] ?? '');
  $pass    = $_POST["pass"] ?? '';

  if ($usuario === '' || $pass === '') {
    echo "error";
    exit;
  }

  $sql = "SELECT id, usuario, password, rol, email, nombre
          FROM usuarios_sistema
          WHERE usuario = ?
          LIMIT 1";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$usuario]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($user && !empty($user["password"]) && password_verify($pass, $user["password"])) {
    session_regenerate_id(true);

    $_SESSION["usuario_id"] = (int)$user["id"];
    $_SESSION["usuario"]    = $user["usuario"];
    $_SESSION["rol"]        = $user["rol"] ?? null;
    $_SESSION["email"]      = $user["email"] ?? null;
    $_SESSION["nombre"]     = $user["nombre"] ?? null;

    echo "ok";
  } else {
    echo "error";
  }
  exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Login</title>
<link rel="stylesheet" href="style.css">
<style>
  .center { max-width: 420px; margin: 40px auto; }
  .form-row { margin: 10px 0; }
  .form-row label { display:block; margin-bottom:6px; }
  .full { width:100%; padding:8px; }
  .error-msg { color:#c0392b; margin:8px 0 0; }
  button { padding:10px 14px; cursor:pointer; }
</style>
</head>
<body>
  <div class="center">
    <h2>Login</h2>

    <div class="form-row">
      <label for="usuario">Usuario</label>
      <input id="usuario" class="full" placeholder="Tu usuario">
    </div>

    <div class="form-row">
      <label for="pass">Contraseña</label>
      <input id="pass" class="full" type="password" placeholder="Contraseña">
    </div>

    <div class="form-row">
      <button onclick="login()">Entrar</button>
      <p id="msg" class="error-msg"></p>
    </div>
  </div>

<script>
async function login() {
  const data = new FormData();
  data.append("usuario", document.querySelector("#usuario").value.trim());
  data.append("pass", document.querySelector("#pass").value);

  const res = await fetch("login.php", { method: "POST", body: data });
  const txt = await res.text();

  if (txt === "ok") {
    location.href = "usuarios.php";
  } else {
    document.querySelector("#msg").textContent = "Credenciales incorrectas";
  }
}
</script>
</body>
</html>
