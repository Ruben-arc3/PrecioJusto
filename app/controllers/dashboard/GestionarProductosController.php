<?php
session_start();
require_once __DIR__ . '/../../models/connection.php';

if (!isset($_SESSION['usuario']) || ($_SESSION['usuario']['tipo'] ?? '') !== 'admin') {
    header('Location: login.php');
    exit;
}

$stmt = $conn->query(
    'SELECT p.*, MIN(pt.precio) AS precio '
    . 'FROM productos p LEFT JOIN producto_tienda pt ON pt.id_producto = p.id_producto '
    . 'GROUP BY p.id_producto, p.nombre_producto, p.Foto, p.marca, p.id_categoria ORDER BY p.id_producto DESC'
);
$productos = $stmt->fetchAll();

require __DIR__ . '/../../views/pages/dashboard/gestionar_productos.php';
