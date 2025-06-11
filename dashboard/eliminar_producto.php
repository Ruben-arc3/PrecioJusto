<?php
session_start();
require_once '../views/conexion.php';

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$id = $_GET['id'] ?? null;
if ($id) {
    $stmt = $conn->prepare("DELETE FROM productos WHERE id_producto = ?");
    $stmt->execute([$id]);
}

header("Location: gestionar_productos.php");
exit;
