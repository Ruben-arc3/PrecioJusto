<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['usuario'])) {
    echo json_encode(['success' => false, 'message' => 'No autenticado']);
    exit;
}

require 'conexion.php';

$id_usuario = $_SESSION['usuario'];
$id_producto = $_POST['producto_id'] ?? null;

if (!$id_producto || !is_numeric($id_producto)) {
    echo json_encode(['success' => false, 'message' => 'ID de producto inválido']);
    exit;
}

$stmt = $conn->prepare("SELECT COUNT(*) FROM favoritos WHERE id_usuario = ? AND id_producto = ?");
$stmt->execute([$id_usuario, $id_producto]);
$esFavorito = $stmt->fetchColumn() > 0;

if ($esFavorito) {
    $stmt = $conn->prepare("DELETE FROM favoritos WHERE id_usuario = ? AND id_producto = ?");
    $stmt->execute([$id_usuario, $id_producto]);
    echo json_encode(['success' => true, 'accion' => 'eliminado']);
} else {
    $stmt = $conn->prepare("INSERT INTO favoritos (id_usuario, id_producto) VALUES (?, ?)");
    $stmt->execute([$id_usuario, $id_producto]);
    echo json_encode(['success' => true, 'accion' => 'agregado']);
}
