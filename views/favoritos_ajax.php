<?php
session_start();
require_once 'conexion.php';
header('Content-Type: application/json');

if (!isset($_SESSION['usuario'])) {
    echo json_encode(['success' => false, 'message' => 'No autenticado']);
    exit;
}

if (!isset($_GET['accion'], $_GET['producto_id'])) {
    echo json_encode(['success' => false, 'message' => 'Parámetros incompletos']);
    exit;
}

$accion = $_GET['accion'];
$productoId = (int)$_GET['producto_id'];
$usuarioId = $_SESSION['usuario']['id'];

if ($accion === 'agregar') {
    $stmt = $conn->prepare("INSERT IGNORE INTO favoritos (id_usuario, id_producto) VALUES (?, ?)");
    $stmt->execute([$usuarioId, $productoId]);
    echo json_encode(['success' => true, 'action' => 'agregado', 'producto_id' => $productoId]);
    exit;
} elseif ($accion === 'quitar') {
    $stmt = $conn->prepare("DELETE FROM favoritos WHERE id_usuario = ? AND id_producto = ?");
    $stmt->execute([$usuarioId, $productoId]);
    echo json_encode(['success' => true, 'action' => 'quitado', 'producto_id' => $productoId]);
    exit;
} else {
    echo json_encode(['success' => false, 'message' => 'Acción desconocida']);
    exit;
}
