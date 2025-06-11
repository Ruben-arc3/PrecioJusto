<?php
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['usuario'])) {
    die("No autorizado");
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Método no permitido");
}

if (!isset($_POST['id_producto']) || !is_numeric($_POST['id_producto'])) {
    die("Datos incompletos");
}

$id_producto = (int)$_POST['id_producto'];
$id_usuario = $_SESSION['usuario']['id'];

try {
    $stmt = $conn->prepare("DELETE FROM favoritos WHERE id_usuario = ? AND id_producto = ?");
    $stmt->execute([$id_usuario, $id_producto]);
    
    if ($stmt->rowCount() > 0) {
        echo "ok";
    } else {
        echo "error";
    }
} catch (PDOException $e) {
    echo "error";
}
?>