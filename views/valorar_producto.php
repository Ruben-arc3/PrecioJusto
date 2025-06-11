<?php
session_start();
require_once 'conexion.php';

header('Content-Type: application/json');

if (!isset($_SESSION['usuario'])) {
    echo json_encode(['success' => false, 'message' => 'No autenticado']);
    exit;
}

if (!isset($_POST['producto_id'], $_POST['puntuacion'])) {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
    exit;
}

$usuarioId = $_SESSION['usuario']['id'];
$productoId = (int)$_POST['producto_id'];
$puntuacion = (int)$_POST['puntuacion'];

if ($puntuacion < 1 || $puntuacion > 5) {
    echo json_encode(['success' => false, 'message' => 'Puntuación inválida']);
    exit;
}

// Insertar o actualizar si ya existe valoración del mismo usuario
$stmt = $conn->prepare("INSERT INTO valoraciones (id_producto, id_usuario, puntuacion, fecha_creacion)
                        VALUES (?, ?, ?, NOW())
                        ON DUPLICATE KEY UPDATE puntuacion = VALUES(puntuacion), fecha_creacion = NOW()");
$stmt->execute([$productoId, $usuarioId, $puntuacion]);

echo json_encode(['success' => true]);
?>
