<?php
session_start();
require_once 'conexion.php';

header('Content-Type: application/json');

// Verificar autenticación
if (!isset($_SESSION['usuario'])) {
    echo json_encode(['success' => false, 'message' => 'No autenticado']);
    exit;
}

// Validar entrada
if (!isset($_POST['accion']) || !isset($_POST['producto_id'])) {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
    exit;
}

$accion = $_POST['accion'];
$productoId = (int)$_POST['producto_id'];
$usuarioId = $_SESSION['usuario']['id'];

try {
    if ($accion === 'agregar') {
        $stmt = $conn->prepare("INSERT IGNORE INTO favoritos (id_usuario, id_producto) VALUES (?, ?)");
        $stmt->execute([$usuarioId, $productoId]);
    } elseif ($accion === 'quitar') {
        $stmt = $conn->prepare("DELETE FROM favoritos WHERE id_usuario = ? AND id_producto = ?");
        $stmt->execute([$usuarioId, $productoId]);
    } else {
        throw new Exception('Acción no válida');
    }
    
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>