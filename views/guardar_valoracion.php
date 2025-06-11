<?php
session_start();
require_once 'conexion.php';

// Solo usuarios autenticados pueden comentar
if (!isset($_SESSION['usuario']['id'])) {
    header('Location: login.php');
    exit;
}

$usuarioId = $_SESSION['usuario']['id'];
$id_producto = isset($_POST['id_producto']) ? (int)$_POST['id_producto'] : 0;
$comentario = isset($_POST['comentario']) ? trim($_POST['comentario']) : '';

// Validación básica
if ($id_producto <= 0 || empty($comentario)) {
    $_SESSION['error_valoracion'] = 'Debes escribir un comentario.';
    header("Location: detalles.php?id=$id_producto");
    exit;
}

// Verificar que el producto existe
$stmtProd = $conn->prepare("SELECT COUNT(*) FROM productos WHERE id_producto = ?");
$stmtProd->execute([$id_producto]);
if ($stmtProd->fetchColumn() == 0) {
    $_SESSION['error_valoracion'] = 'Producto no encontrado.';
    header("Location: index.php");
    exit;
}

// Obtener última puntuación del usuario para ese producto
$stmtPunt = $conn->prepare("SELECT puntuacion FROM valoraciones WHERE id_usuario = ? AND id_producto = ? AND puntuacion IS NOT NULL ORDER BY fecha_creacion DESC LIMIT 1");
$stmtPunt->execute([$usuarioId, $id_producto]);
$puntuacion = $stmtPunt->fetchColumn();

// Insertar comentario nuevo con la puntuación previa (si existe)
$stmtInsert = $conn->prepare("
    INSERT INTO valoraciones (id_producto, id_usuario, puntuacion, comentario, fecha_creacion)
    VALUES (?, ?, ?, ?, NOW())
");
$stmtInsert->execute([$id_producto, $usuarioId, $puntuacion, $comentario]);

$_SESSION['success_valoracion'] = 'Comentario guardado correctamente.';
header("Location: detalles.php?id=$id_producto");
exit;
?>
