<?php
session_start();
require_once '../views/conexion.php';

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
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Producto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2>Editar Producto</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Nombre del Producto</label>
            <input type="text" name="nombre_producto" class="form-control" value="<?= htmlspecialchars($producto['nombre_producto']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Precio</label>
            <input type="number" name="precio" class="form-control" value="<?= $producto['precio'] ?>" step="0.01" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Marca</label>
            <input type="text" name="marca" class="form-control" value="<?= htmlspecialchars($producto['marca']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Foto actual</label><br>
            <?php if (!empty($producto['foto'])): ?>
                <img src="../<?= $producto['foto'] ?>" alt="Foto actual" width="100">
            <?php else: ?>
                <p>No hay imagen</p>
            <?php endif; ?>
        </div>
        <div class="mb-3">
            <label class="form-label">Nueva foto (opcional)</label>
            <input type="file" name="foto" class="form-control" accept="image/*">
        </div>
        <div class="mb-3">
            <label class="form-label">Tienda</label>
            <select name="id_tienda" class="form-select" required>
                <option value="">Selecciona una tienda</option>
                <?php foreach ($tiendas as $tienda): ?>
                    <option value="<?= $tienda['id_tienda'] ?>" <?= $producto['id_tienda'] == $tienda['id_tienda'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($tienda['nombre_tienda']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Categoría</label>
            <select name="id_categoria" class="form-select" required>
                <option value="">Selecciona una categoría</option>
                <?php foreach ($categorias as $categoria): ?>
                    <option value="<?= $categoria['id_categoria'] ?>" <?= $producto['id_categoria'] == $categoria['id_categoria'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($categoria['nombre_categoria']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="gestionar_productos.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
</body>
</html>
