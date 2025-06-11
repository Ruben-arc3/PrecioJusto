<?php
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['usuario'])) {
    echo json_encode(['success' => false, 'error' => 'No autenticado']);
    exit;
}

$id_usuario = $_SESSION['usuario']['id'];
$id_producto = (int)($_POST['producto_id'] ?? 0);
$puntuacion = (int)($_POST['puntuacion'] ?? 0);

if ($id_producto <= 0 || $puntuacion < 1 || $puntuacion > 5) {
    echo json_encode(['success' => false, 'error' => 'Datos inválidos']);
    exit;
}

// Insertar o actualizar la valoración
$stmt = $conn->prepare("
    INSERT INTO valoraciones (id_producto, id_usuario, puntuacion, fecha_creacion)
    VALUES (?, ?, ?, NOW())
    ON DUPLICATE KEY UPDATE puntuacion = VALUES(puntuacion), fecha_creacion = NOW()
");
$stmt->execute([$id_producto, $id_usuario, $puntuacion]);

echo json_encode(['success' => true]);
