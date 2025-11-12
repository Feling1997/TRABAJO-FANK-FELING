<?php
require_once __DIR__ . '/../config/base_datos.php';
session_start();
if (!isset($_SESSION["usuario_id"])) {
  header("Location: ../login.php");
  exit;
}
?>
<?php
$alert = null;
if (!empty($_GET['ok'])) {
  $alert = ['type'=>'success', 'msg'=>'Operación realizada correctamente.'];
} elseif (!empty($_GET['error'])) {
  $alert = ['type'=>'danger', 'msg'=> htmlspecialchars($_GET['error'])];
}

$res = $conexion->query("
  SELECT id, nombre_completo, email, telefono, dni, direccion, estado
  FROM usuarios
  ORDER BY id DESC
");
$usuarios = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
?>
<!DOCTYPE html>
<html lang="es">
<head>

  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Usuarios</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <title>Usuarios - Listado</title>
</head>
<body class="bg-light">
  <div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-3">
      <h1 class="h3 m-0">Usuarios</h1>
      <div class="d-flex gap-2">
        <input id="q" type="text" class="form-control" placeholder="Buscar por nombre, email o DNI..." oninput="filtrar()" style="min-width:280px">
        <a class="btn btn-success" href="crear.php">+ Nuevo usuario</a>
      </div>
    </div>

    <?php if ($alert): ?>
      <div class="alert alert-<?= $alert['type'] ?> alert-dismissible fade show" role="alert">
        <?= $alert['msg'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif; ?>

    <div class="card shadow-sm">
      <div class="table-responsive">
        <table id="tabla" class="table table-hover table-striped align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>ID</th>
              <th>Nombre</th>
              <th>Email</th>
              <th>Tel</th>
              <th>DNI</th>
              <th>Dirección</th>
              <th>Estado</th>
              <th class="text-end">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($usuarios as $u): ?>
              <tr>
                <td><?= htmlspecialchars($u['id']) ?></td>
                <td><?= htmlspecialchars($u['nombre_completo']) ?></td>
                <td><?= htmlspecialchars($u['email']) ?></td>
                <td><?= htmlspecialchars($u['telefono']) ?></td>
                <td><?= htmlspecialchars($u['dni']) ?></td>
                <td><?= htmlspecialchars($u['direccion']) ?></td>
                <td>
                  <?php if ((string)$u['estado']==='1' || $u['estado']==='activo' || $u['estado']==='ACTIVO'): ?>
                    <span class="badge text-bg-success">Activo</span>
                  <?php else: ?>
                    <span class="badge text-bg-warning text-dark">Inactivo</span>
                  <?php endif; ?>
                </td>
                <td class="text-end">
                  <a class="btn btn-sm btn-outline-secondary" href="detalle.php?id=<?= (int)$u['id'] ?>">Detalle</a>
                  <a class="btn btn-sm btn-primary" href="editar.php?id=<?= (int)$u['id'] ?>">Editar</a>
                  <button class="btn btn-sm btn-danger" onclick="confirmarEliminar(<?= (int)$u['id'] ?>)">Eliminar</button>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

    <form id="form-eliminar" method="post" action="eliminar.php" class="d-none">
      <input type="hidden" name="id" id="del-id">
    </form>
  </div>

<script>


window.addEventListener('DOMContentLoaded', () => {
  for (const tr of document.querySelectorAll('#tabla tbody tr')) {
    const idCelda = tr.querySelector('td:first-child'); // primera columna
    if (!idCelda) continue;

    const id = idCelda.textContent.trim();
    if (id === '1') {  
      tr.style.display = 'none';
    }
  }
});


function filtrar() {
  const q = document.getElementById('q')?.value.toLowerCase() || '';

  for (const tr of document.querySelectorAll('#tabla tbody tr')) {
    const texto = tr.innerText.toLowerCase();
    const id = tr.querySelector('td:first-child')?.textContent.trim();
    const esAdmin = id === '1';

    tr.style.display = (texto.includes(q) && !esAdmin) ? '' : 'none';
  }
}
function confirmarEliminar(id) {
  if (!confirm("¿Eliminar este usuario? Esta acción no se puede deshacer.")) return;
  document.getElementById('del-id').value = id;
  document.getElementById('form-eliminar').submit();
}
</script>
</body>
</html>
