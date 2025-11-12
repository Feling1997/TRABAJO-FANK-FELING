<?php
require_once __DIR__ . '/../config/base_datos.php';
session_start();
if (!isset($_SESSION["usuario_id"])) {
  header("Location: ../login.php");
  exit;
}
?>
<?php
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
  header("Location: index.php");
  exit;
}
$id = isset($_POST["id"]) ? (int)$_POST["id"] : 0;
if ($id <= 0) { header("Location: index.php"); exit; }

$stmt = $conexion->prepare("DELETE FROM usuarios WHERE id = ?");
if ($stmt) {
  $stmt->bind_param("i", $id);
  if (!$stmt->execute()) {
    $msg = urlencode("No se puede eliminar este usuario. Verifique si posee préstamos asociados.");
    header("Location: index.php?error=" . $msg);
    exit;
  }
  $stmt->close();
}
header("Location: index.php?ok=1");
exit;
