<?php
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['usuario']['id'])) {
    die("No autorizado");
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Método no permitido");
}

if (!isset($_POST['id_comentario']) || !is_numeric($_POST['id_comentario'])) {
    die("Datos incompletos");
}

$id_comentario = (int)$_POST['id_comentario'];

// Verificar que el usuario es dueño del comentario
$stmt = $conn->prepare("
    SELECT c.id_usuario 
    FROM comentarios c
    WHERE c.id_comentario = ? AND c.id_usuario = ?
");
$stmt->execute([$id_comentario, $_SESSION['usuario']['id']]);

if ($stmt->rowCount() === 0) {
    die("No tienes permiso para eliminar este comentario");
}

try {
    $stmt = $conn->prepare("DELETE FROM comentarios WHERE id_comentario = ?");
    $stmt->execute([$id_comentario]);
    echo "ok";
} catch (PDOException $e) {
    die("Error al eliminar el comentario");
}
?>