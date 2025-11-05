<?php
session_start();
require "db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $email = $_POST["email"];
  $pass = $_POST["pass"];

  $stmt = $pdo->prepare("SELECT * FROM cuentas WHERE email=? AND pass=?");
  $stmt->execute([$email, $pass]);
  $user = $stmt->fetch();

  if ($user) {
    $_SESSION["usuario"] = $user["email"];
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
</head>
<body>
<h2>Login</h2>
<input id="email" placeholder="Email"><br>
<input id="pass" type="password" placeholder="Contraseña"><br>
<button onclick="login()">Entrar</button>
<p id="msg"></p>

<script>
async function login() {
  const data = new FormData();
  data.append("email", document.querySelector("#email").value);
  data.append("pass", document.querySelector("#pass").value);
  const res = await fetch("login.php", { method: "POST", body: data });
  const txt = await res.text();
  if (txt === "ok") location.href = "usuarios.php";
  else document.querySelector("#msg").textContent = "Credenciales incorrectas";
}
</script>
</body>
</html>
