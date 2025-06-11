<?php
require_once 'conexion.php';
session_start();

if (!isset($_SESSION['usuario']) || !isset($_POST['id_producto'])) {
    echo json_encode(['status' => 'error']);
    exit;
}

$id_usuario = $_SESSION['usuario']['id_usuario'];
$id_producto = (int)$_POST['id_producto'];

$stmt = $conn->prepare("SELECT id FROM favoritos WHERE id_usuario = ? AND id_producto = ?");
$stmt->execute([$id_usuario, $id_producto]);
$existe = $stmt->fetch();

if ($existe) {
    $stmt = $conn->prepare("DELETE FROM favoritos WHERE id_usuario = ? AND id_producto = ?");
    $stmt->execute([$id_usuario, $id_producto]);
    echo json_encode(['status' => 'eliminado']);
} else {
    $stmt = $conn->prepare("INSERT INTO favoritos (id_usuario, id_producto) VALUES (?, ?)");
    $stmt->execute([$id_usuario, $id_producto]);
    echo json_encode(['status' => 'agregado']);
}
?>
