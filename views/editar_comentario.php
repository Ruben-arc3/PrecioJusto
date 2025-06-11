<?php
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['usuario']['id'])) {
    die("No autorizado");
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Método no permitido");
}

if (!isset($_POST['id_comentario']) || !is_numeric($_POST['id_comentario']) || 
    !isset($_POST['contenido']) || empty(trim($_POST['contenido']))) {
    die("Datos incompletos");
}

$id_comentario = (int)$_POST['id_comentario'];
$contenido = trim($_POST['contenido']);

// Verificar que el usuario es dueño del comentario
$stmt = $conn->prepare("
    SELECT c.id_usuario 
    FROM comentarios c
    WHERE c.id_comentario = ? AND c.id_usuario = ?
");
$stmt->execute([$id_comentario, $_SESSION['usuario']['id']]);

if ($stmt->rowCount() === 0) {
    die("No tienes permiso para editar este comentario");
}

try {
    $stmt = $conn->prepare("UPDATE comentarios SET contenido = ? WHERE id_comentario = ?");
    $stmt->execute([$contenido, $id_comentario]);
    echo "ok";
} catch (PDOException $e) {
    die("Error al editar el comentario");
}
?>