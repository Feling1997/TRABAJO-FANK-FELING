<?php
require "base_datos.php";
session_start();

if (!isset($_SESSION["usuario_id"])) {
  header("Location: login.php");
  exit;
}

if (isset($_GET["accion"])) {
  header("Content-Type: application/json");

  if ($_GET["accion"] === "listar") {
    $res = $pdo->query("SELECT * FROM usuarios ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($res);
    exit;
  }

  if ($_GET["accion"] === "agregar") {
    $data = json_decode(file_get_contents("php://input"), true);
    $stmt = $pdo->prepare("INSERT INTO usuarios(nombre,email,pass,rol) VALUES (?,?,?,?)");
    $stmt->execute([$data["nombre"], $data["email"], $data["pass"], $data["rol"]]);
    echo json_encode(["ok" => true]);
    exit;
  }

  if ($_GET["accion"] === "editar") {
    $data = json_decode(file_get_contents("php://input"), true);
    $stmt = $pdo->prepare("UPDATE usuarios SET nombre=?, email=?, pass=?, rol=? WHERE id=?");
    $stmt->execute([$data["nombre"], $data["email"], $data["pass"], $data["rol"], $data["id"]]);
    echo json_encode(["ok" => true]);
    exit;
  }

  if ($_GET["accion"] === "eliminar") {
    $id = (int)$_GET["id"];
    $pdo->query("DELETE FROM usuarios WHERE id=$id");
    echo json_encode(["ok" => true]);
    exit;
  }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Gestión de Usuarios</title>
  <link rel="stylesheet" href="style.css">
  <style>
    body { background: #f4f4f4; font-family: Arial, sans-serif; }
    .container { max-width: 850px; margin: 40px auto; background: white; padding: 20px 30px; border-radius: 10px; box-shadow: 0 0 8px rgba(0,0,0,0.1); }
    h2 { text-align: center; margin-bottom: 20px; }
    .form-row { margin: 10px 0; }
    input, select, button { padding: 8px; margin-right: 5px; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    th, td { padding: 10px; border-bottom: 1px solid #ddd; }
    th { background: #2c3e50; color: white; }
    tr:hover { background: #ecf0f1; }
    .acciones button { margin-right: 5px; cursor: pointer; }
    .logout { text-align: right; margin-top: 10px; }
    .logout a { color: #c0392b; text-decoration: none; font-weight: bold; }
    .logout a:hover { text-decoration: underline; }
  </style>
</head>
<body>
  <div class="container">
    <h2>Gestión de Usuarios del Sistema</h2>

    <div class="form-row">
      <input id="id" type="hidden">
      <input id="nombre" placeholder="Nombre">
      <input id="email" placeholder="Email">
      <input id="tel" placeholder="Teléfono">
      <input id="dni" placeholder="DNI/Documento">
      <input id="direccion" placeholder="Dirección">
      <select id="estado">
        <option value="activo">activo</option>
        <option value="suspendido">suspendido</option>
      </select>
      <button onclick="guardar()">Guardar</button>
      <button onclick="cancelar()">Cancelar</button>
    </div>

    <table id="tabla"></table>

    <div class="logout">
      <a href="logout.php">Cerrar sesión</a>
    </div>
  </div>

<script>
const $ = s => document.querySelector(s);
let editando = false;

async function listar() {
  const res = await fetch("?accion=listar");
  const data = await res.json();
  let html = `
    <tr>
      <th>ID</th><th>Nombre</th><th>Email</th>
      <th>Contraseña</th><th>Rol</th><th>Acciones</th>
    </tr>`;
  data.forEach(u => {
    html += `
    <tr>
      <td>${u.id}</td>
      <td>${u.nombre}</td>
      <td>${u.email}</td>
      <td>${u.tel}</td>
      <td>${u.direc}</td>
      <td>${u.estado}</td>
      <td class="acciones">
        <button onclick='editar(${u.id},"${u.nombre}","${u.email}","${u.pass}","${u.rol}")'>Editar</button>
        <button onclick="eliminar(${u.id})">Eliminar</button>
      </td>
    </tr>`;
  });
  $("#tabla").innerHTML = html;
}

async function guardar() {
  const u = {
    id: $("#id").value,
    nombre: $("#nombre").value,
    email: $("#email").value,
    tel: $("#telefono").value,
    direc: $("#direccion").value
    estado: $("#estado").value

  };
  const accion = editando ? "editar" : "agregar";
  await fetch(`?accion=${accion}`, {
    method: "POST",
    body: JSON.stringify(u)
  });
  cancelar();
  listar();
}

function editar(id, nombre, email, tel, direc, estado) {
  $("#id").value = id;
  $("#nombre").value = nombre;
  $("#email").value = email;
  $("#telefono").value = tel;
  $("#direccion").value = direc;
  $("#estado").value = "activo";

  editando = true;
}

function cancelar() {
  $("#id").value = "";
  $("#nombre").value = "";
  $("#email").value = "";
  $("#dni").value = "";
  $("#telefono").value = "";
  $("#direccion").value = "";
  $("#estado").value = "activo";
  editando = false;
}

async function eliminar(id) {
  if (!confirm("¿Eliminar este usuario?")) return;
  await fetch(`?accion=eliminar&id=${id}`);
  listar();
}

listar();
</script>
</body>
</html>
