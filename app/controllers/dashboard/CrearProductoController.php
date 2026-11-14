<?php
session_start();
require_once __DIR__ . '/../../models/connection.php';

if (!isset($_SESSION['usuario']) || ($_SESSION['usuario']['tipo'] ?? '') !== 'admin') {
    header('Location: login.php');
    exit;
}

$tiendas = $conn->query("SELECT id_tienda, nombre_tienda FROM tiendas ORDER BY nombre_tienda")->fetchAll();
$categorias = $conn->query("SELECT id_categoria, nombre_categoria FROM categorias ORDER BY nombre_categoria")->fetchAll();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre_producto'] ?? '');
    $precio = filter_var($_POST['precio'] ?? null, FILTER_VALIDATE_FLOAT);
    $marca = trim($_POST['marca'] ?? '');
    $idTienda = filter_var($_POST['id_tienda'] ?? null, FILTER_VALIDATE_INT);
    $idCategoria = filter_var($_POST['id_categoria'] ?? null, FILTER_VALIDATE_INT);

    if ($nombre === '' || $marca === '' || $precio === false || $precio <= 0 || !$idTienda || !$idCategoria) {
        $error = 'Completa todos los campos con valores válidos.';
    } elseif (!isset($_FILES['foto']) || $_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
        $error = 'Selecciona una imagen válida para el producto.';
    } else {
        $imageInfo = @getimagesize($_FILES['foto']['tmp_name']);
        $extension = $imageInfo['mime'] ?? '';
        $extensions = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp', 'image/gif' => 'gif'];

        if (!$imageInfo || !isset($extensions[$extension])) {
            $error = 'La imagen debe ser JPG, PNG, WEBP o GIF.';
        } else {
            $filename = bin2hex(random_bytes(12)) . '.' . $extensions[$extension];
            $imagePath = __DIR__ . '/../../img/' . $filename;
            $photoUrl = '../img/' . $filename;

            if (!move_uploaded_file($_FILES['foto']['tmp_name'], $imagePath)) {
                $error = 'No se pudo guardar la imagen en app/img.';
            } else {
                try {
                    $storeQuery = $conn->prepare('SELECT url FROM tiendas WHERE id_tienda = ?');
                    $storeQuery->execute([$idTienda]);
                    $storeUrl = $storeQuery->fetchColumn();
                    if (!$storeUrl) {
                        throw new RuntimeException('La tienda seleccionada no existe.');
                    }

                    $conn->beginTransaction();
                    $insertProduct = $conn->prepare(
                        'INSERT INTO productos (nombre_producto, Foto, marca, id_categoria) VALUES (?, ?, ?, ?)'
                    );
                    $insertProduct->execute([$nombre, $photoUrl, $marca, $idCategoria]);
                    $productId = (int)$conn->lastInsertId();

                    $insertPrice = $conn->prepare(
                        'INSERT INTO producto_tienda (id_producto, id_tienda, precio, url) VALUES (?, ?, ?, ?)'
                    );
                    $insertPrice->execute([$productId, $idTienda, $precio, $storeUrl]);
                    $conn->commit();

                    header('Location: gestionar_productos.php');
                    exit;
                } catch (Throwable $exception) {
                    if ($conn->inTransaction()) {
                        $conn->rollBack();
                    }
                    @unlink($imagePath);
                    error_log('Error al crear producto: ' . $exception->getMessage());
                    $error = 'No se pudo guardar el producto. Verifica la base de datos y vuelve a intentarlo.';
                }
            }
        }
    }
}

require __DIR__ . '/../../views/pages/dashboard/crear_producto.php';
