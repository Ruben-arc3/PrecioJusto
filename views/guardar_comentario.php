<?php
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['usuario']['id'])) {
    die("No autorizado");
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Método no permitido");
}

if (!isset($_POST['id_producto']) || !is_numeric($_POST['id_producto']) || 
    !isset($_POST['comentario']) || empty(trim($_POST['comentario']))) {
    die("Datos incompletos");
}

$id_producto = (int)$_POST['id_producto'];
$id_usuario = (int)$_SESSION['usuario']['id'];
$comentario = trim($_POST['comentario']);

try {
    $stmt = $conn->prepare("INSERT INTO comentarios (id_producto, id_usuario, contenido) VALUES (?, ?, ?)");
    $stmt->execute([$id_producto, $id_usuario, $comentario]);
    echo "ok";
} catch (PDOException $e) {
    die("Error al guardar el comentario");
}
?>