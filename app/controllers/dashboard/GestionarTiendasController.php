<?php
session_start();
require_once __DIR__ . '/../../models/connection.php';

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$stmt = $conn->query("SELECT * FROM tiendas");
$tiendas = $stmt->fetchAll(PDO::FETCH_ASSOC);

require __DIR__ . '/../../views/pages/dashboard/gestionar_tiendas.php';
