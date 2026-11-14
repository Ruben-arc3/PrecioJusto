<?php
require_once __DIR__ . '/../../models/connection.php';

session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Obtener estadísticas
$productos = $conn->query("SELECT COUNT(*) FROM productos")->fetchColumn();
$usuarios = $conn->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
$tiendas = $conn->query("SELECT COUNT(*) FROM tiendas")->fetchColumn();

// Obtener datos para el gráfico de usuarios por mes


require __DIR__ . '/../../views/pages/dashboard/admin.php';
