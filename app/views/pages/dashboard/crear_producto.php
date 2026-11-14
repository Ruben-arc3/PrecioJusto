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
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Nombre del Producto</label>
            <input type="text" name="nombre_producto" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Precio en la tienda seleccionada</label>
            <input type="number" name="precio" class="form-control" min="0.01" step="0.01" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Marca</label>
            <input type="text" name="marca" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Foto del Producto</label>
            <input type="file" name="foto" class="form-control" accept="image/jpeg,image/png,image/webp,image/gif" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Tienda</label>
            <select name="id_tienda" class="form-select" required>
                <option value="">Selecciona una tienda</option>
                <?php foreach ($tiendas as $tienda): ?>
                    <option value="<?= (int)$tienda['id_tienda'] ?>"><?= htmlspecialchars($tienda['nombre_tienda']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Categoría</label>
            <select name="id_categoria" class="form-select" required>
                <option value="">Selecciona una categoría</option>
                <?php foreach ($categorias as $categoria): ?>
                    <option value="<?= (int)$categoria['id_categoria'] ?>"><?= htmlspecialchars($categoria['nombre_categoria']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="gestionar_productos.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
</body>
</html>
