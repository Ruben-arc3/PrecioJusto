<?php
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['usuario']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$id_comentario = $_POST['id_comentario'];
$id_producto = $_POST['id_producto'];
$contenido = trim($_POST['comentario']);

// Verifica propiedad del comentario
$stmt = $conn->prepare("SELECT * FROM comentarios WHERE id_comentario = ? AND id_usuario = ?");
$stmt->execute([$id_comentario, $_SESSION['usuario']['id']]);

if ($stmt->rowCount() > 0 && !empty($contenido)) {
    $update = $conn->prepare("UPDATE comentarios SET contenido = ?, fecha_creacion = NOW() WHERE id_comentario = ?");
    $update->execute([$contenido, $id_comentario]);
}

header("Location: detalles.php?id=" . urlencode($id_producto));
exit;
