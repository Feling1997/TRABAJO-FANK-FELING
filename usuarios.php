<?php
require "db.php";
session_start();

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
    $stmt->execute([
      $data["nombre"],
      $data["email"],
      $data["pass"],
      $data["rol"]
    ]);
    echo json_encode(["ok" => true]);
    exit;
  }

  if ($_GET["accion"] === "editar") {
    $data = json_decode(file_get_contents("php://input"), true);
    $stmt = $pdo->prepare("UPDATE usuarios SET nombre=?, email=?, pass=?, rol=? WHERE id=?");
    $stmt->execute([
      $data["nombre"],
      $data["email"],
      $data["pass"],
      $data["rol"],
      $data["id"]
    ]);
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
<title>CRUD de Usuarios</title>
</head>
<body>
<h2>Gestión de Usuarios del Sistema</h2>

<input id="id" type="hidden">
<input id="nombre" placeholder="Nombre">
<input id="email" placeholder="Email">
<input id="pass" type="password" placeholder="Contraseña">
<select id="rol">
  <option value="admin">admin</option>
  <option value="empleado">empleado</option>
</select>
<button onclick="guardar()">Guardar</button>
<button onclick="cancelar()">Cancelar</button>

<table border="1" id="tabla"></table>

<script>
const $ = s => document.querySelector(s);
let editando = false;

async function listar() {
  const res = await fetch("?accion=listar");
  const data = await res.json();
  let html = "<tr><th>ID</th><th>Nombre</th><th>Email</th><th>Contraseña</th><th>Rol</th><th>Acciones</th></tr>";
  data.forEach(u => {
    html += `<tr>
      <td>${u.id}</td>
      <td>${u.nombre}</td>
      <td>${u.email}</td>
      <td>${u.pass}</td>
      <td>${u.rol}</td>
      <td>
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
    pass: $("#pass").value,
    rol: $("#rol").value
  };
  const accion = editando ? "editar" : "agregar";
  await fetch(`?accion=${accion}`, {
    method: "POST",
    body: JSON.stringify(u)
  });
  cancelar();
  listar();
}

function editar(id, nombre, email, pass, rol) {
  $("#id").value = id;
  $("#nombre").value = nombre;
  $("#email").value = email;
  $("#pass").value = pass;
  $("#rol").value = rol;
  editando = true;
}

function cancelar() {
  $("#id").value = "";
  $("#nombre").value = "";
  $("#email").value = "";
  $("#pass").value = "";
  $("#rol").value = "empleado";
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
