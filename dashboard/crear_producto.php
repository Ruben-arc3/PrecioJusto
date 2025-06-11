<?php
session_start();
require_once '../views/conexion.php';

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
$rutaDestino = __DIR__ . '/../views/' . $nombreArchivo; // Guarda en app/views/
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
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo Producto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2>Nuevo Producto</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Nombre del Producto</label>
            <input type="text" name="nombre_producto" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Precio</label>
            <input type="number" name="precio" class="form-control" step="0.01" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Marca</label>
            <input type="text" name="marca" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Foto del Producto</label>
            <input type="file" name="foto" class="form-control" accept="image/*" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Tienda</label>
            <select name="id_tienda" class="form-select" required>
                <option value="">Selecciona una tienda</option>
                <?php foreach ($tiendas as $tienda): ?>
                    <option value="<?= $tienda['id_tienda'] ?>"><?= htmlspecialchars($tienda['nombre_tienda']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Categoría</label>
            <select name="id_categoria" class="form-select" required>
                <option value="">Selecciona una categoría</option>
                <?php foreach ($categorias as $categoria): ?>
                    <option value="<?= $categoria['id_categoria'] ?>"><?= htmlspecialchars($categoria['nombre_categoria']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="gestionar_productos.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
</body>
</html>
