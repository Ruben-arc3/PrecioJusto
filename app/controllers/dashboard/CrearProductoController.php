<?php
session_start();
require_once __DIR__ . '/../../models/connection.php';

// Solo admin puede acceder
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Obtener tiendas y categorías
$tiendas = $conn->query("SELECT id_tienda, nombre_tienda FROM tiendas")->fetchAll(PDO::FETCH_ASSOC);
$categorias = $conn->query("SELECT id_categoria, nombre_categoria FROM categorias")->fetchAll(PDO::FETCH_ASSOC);

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre_producto'];
    $precio = $_POST['precio'];
    $marca = $_POST['marca'];
    $id_tienda = $_POST['id_tienda'];
    $id_categoria = $_POST['id_categoria'];

    $foto_url = '';
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
     $nombreArchivo = time() . '_' . basename($_FILES['foto']['name']);
$rutaDestino = __DIR__ . '/../../views/' . $nombreArchivo; // Guarda en app/views/
$foto_url = 'views/' . $nombreArchivo; // Ruta relativa desde "app"

if (move_uploaded_file($_FILES['foto']['tmp_name'], $rutaDestino)) {
    $foto_url = 'img/' . $nombreArchivo; // lo guardas en la BD
}

    }

    // Insertar en BD
    $stmt = $conn->prepare("INSERT INTO productos (nombre_producto, precio, foto, url, marca, id_tienda, id_categoria) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$nombre, $precio, $foto_url, $foto_url, $marca, $id_tienda, $id_categoria]);

    // Redirigir al listado
    header("Location: gestionar_productos.php");
    exit;
}

require __DIR__ . '/../../views/pages/dashboard/crear_producto.php';
