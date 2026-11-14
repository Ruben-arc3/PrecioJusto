<?php
session_start();
require_once __DIR__ . '/../../models/connection.php';

if (!isset($_SESSION['usuario']) || ($_SESSION['usuario']['tipo'] ?? '') !== 'admin') {
    header('Location: login.php');
    exit;
}

$id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
if (!$id) {
    http_response_code(400);
    exit('ID de producto no válido.');
}

$tiendas = $conn->query("SELECT id_tienda, nombre_tienda FROM tiendas ORDER BY nombre_tienda")->fetchAll();
$categorias = $conn->query("SELECT id_categoria, nombre_categoria FROM categorias ORDER BY nombre_categoria")->fetchAll();
$productQuery = $conn->prepare(
    'SELECT p.*, pt.id_tienda, pt.precio FROM productos p '
    . 'LEFT JOIN producto_tienda pt ON pt.id_producto = p.id_producto '
    . 'WHERE p.id_producto = ? ORDER BY pt.precio ASC LIMIT 1'
);
$productQuery->execute([$id]);
$producto = $productQuery->fetch();
if (!$producto) {
    http_response_code(404);
    exit('Producto no encontrado.');
}
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre_producto'] ?? '');
    $precio = filter_var($_POST['precio'] ?? null, FILTER_VALIDATE_FLOAT);
    $marca = trim($_POST['marca'] ?? '');
    $idTienda = filter_var($_POST['id_tienda'] ?? null, FILTER_VALIDATE_INT);
    $idCategoria = filter_var($_POST['id_categoria'] ?? null, FILTER_VALIDATE_INT);
    $photoUrl = $producto['Foto'];
    $newImagePath = null;

    if ($nombre === '' || $marca === '' || $precio === false || $precio <= 0 || !$idTienda || !$idCategoria) {
        $error = 'Completa todos los campos con valores válidos.';
    } elseif (isset($_FILES['foto']) && $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE) {
        if ($_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
            $error = 'No se pudo recibir la imagen seleccionada.';
        } else {
            $imageInfo = @getimagesize($_FILES['foto']['tmp_name']);
            $mime = $imageInfo['mime'] ?? '';
            $extensions = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp', 'image/gif' => 'gif'];
            if (!$imageInfo || !isset($extensions[$mime])) {
                $error = 'La imagen debe ser JPG, PNG, WEBP o GIF.';
            } else {
                $filename = bin2hex(random_bytes(12)) . '.' . $extensions[$mime];
                $newImagePath = __DIR__ . '/../../img/' . $filename;
                $photoUrl = '../img/' . $filename;
                if (!move_uploaded_file($_FILES['foto']['tmp_name'], $newImagePath)) {
                    $error = 'No se pudo guardar la imagen en app/img.';
                }
            }
        }
    }

    if ($error === '') {
        try {
            $storeQuery = $conn->prepare('SELECT url FROM tiendas WHERE id_tienda = ?');
            $storeQuery->execute([$idTienda]);
            $storeUrl = $storeQuery->fetchColumn();
            if (!$storeUrl) {
                throw new RuntimeException('La tienda seleccionada no existe.');
            }

            $conn->beginTransaction();
            $updateProduct = $conn->prepare(
                'UPDATE productos SET nombre_producto = ?, Foto = ?, marca = ?, id_categoria = ? WHERE id_producto = ?'
            );
            $updateProduct->execute([$nombre, $photoUrl, $marca, $idCategoria, $id]);

            $upsertPrice = $conn->prepare(
                'INSERT INTO producto_tienda (id_producto, id_tienda, precio, url) VALUES (?, ?, ?, ?) '
                . 'ON DUPLICATE KEY UPDATE precio = VALUES(precio), url = VALUES(url)'
            );
            $upsertPrice->execute([$id, $idTienda, $precio, $storeUrl]);
            $conn->commit();

            header('Location: gestionar_productos.php');
            exit;
        } catch (Throwable $exception) {
            if ($conn->inTransaction()) {
                $conn->rollBack();
            }
            if ($newImagePath !== null) {
                @unlink($newImagePath);
            }
            error_log('Error al actualizar producto: ' . $exception->getMessage());
            $error = 'No se pudo actualizar el producto. Verifica la base de datos y vuelve a intentarlo.';
        }
    }
}

require __DIR__ . '/../../views/pages/dashboard/editar_producto.php';
