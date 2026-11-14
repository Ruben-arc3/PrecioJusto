<?php
session_start();
require_once __DIR__ . '/../../models/connection.php';

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Validar ID del producto
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('ID de producto no válido');
}

$id = $_GET['id'];

// Obtener tiendas y categorías
$tiendas = $conn->query("SELECT id_tienda, nombre_tienda FROM tiendas")->fetchAll(PDO::FETCH_ASSOC);
$categorias = $conn->query("SELECT id_categoria, nombre_categoria FROM categorias")->fetchAll(PDO::FETCH_ASSOC);

// Obtener datos del producto
$stmt = $conn->prepare("SELECT * FROM productos WHERE id_producto = ?");
$stmt->execute([$id]);
$producto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$producto) {
    die("Producto no encontrado.");
}

// Procesar actualización
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre_producto'];
    $precio = $_POST['precio'];
    $marca = $_POST['marca'];
    $id_tienda = $_POST['id_tienda'];
    $id_categoria = $_POST['id_categoria'];

    $foto_url = $producto['foto']; // Usar imagen actual por defecto

    // Si se sube una nueva imagen
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $nombreArchivo = time() . '_' . basename($_FILES['foto']['name']);
        $rutaDestino = '../uploads/' . $nombreArchivo;

        if (move_uploaded_file($_FILES['foto']['tmp_name'], $rutaDestino)) {
            $foto_url = 'uploads/' . $nombreArchivo;
        }
    }

    // Actualizar producto
    $stmt = $conn->prepare("UPDATE productos SET nombre_producto = ?, precio = ?, foto = ?, url = ?, marca = ?, id_tienda = ?, id_categoria = ? WHERE id_producto = ?");
    $stmt->execute([$nombre, $precio, $foto_url, $foto_url, $marca, $id_tienda, $id_categoria, $id]);

    header("Location: gestionar_productos.php");
    exit;
}

require __DIR__ . '/../../views/pages/dashboard/editar_producto.php';
