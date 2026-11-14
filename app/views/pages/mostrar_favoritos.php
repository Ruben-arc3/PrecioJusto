<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Favoritos - PrecioJusto</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/estilosfavoritos.css">
    <link rel="stylesheet" href="../css/favoritos-page.css">
</head>
<body>
    <!-- Navbar (se mantiene igual) -->
    <!-- ... -->

    <div class="container py-3">
        <div class="row mb-3">
            <div class="col-12">
                <div class="favorites-header">
                    <h2 class="fw-bold mb-1"><i class="fas fa-heart text-danger me-2"></i>Mis Favoritos</h2>
                    <p class="favorites-count"><?= count($favoritos) ?> producto<?= count($favoritos) !== 1 ? 's' : '' ?> guardado<?= count($favoritos) !== 1 ? 's' : '' ?></p>
                </div>
            </div>
        </div>

        <?php if (empty($favoritos)): ?>
            <div class="empty-favorites">
                <i class="fas fa-heart-broken"></i>
                <h3>Tu lista de favoritos está vacía</h3>
                <p class="text-muted">Agrega productos a tus favoritos para verlos aquí</p>
                <a href="productos.php" class="btn btn-success explore-btn">
                    <i class="fas fa-store me-1"></i> Explorar productos
                </a>
            </div>
        <?php else: ?>
            <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 g-3">
                <?php foreach ($favoritos as $producto): ?>
                    <div class="col">
                        <div class="card product-card h-100">
                            <button class="btn-eliminar-favorito" data-id="<?= $producto['id_producto'] ?>" title="Eliminar de favoritos">
                                <i class="fas fa-times"></i>
                            </button>
                            <div class="card-img-container">
                                <img src="<?= htmlspecialchars($producto['Foto']) ?>" 
                                     class="card-img-top" 
                                     alt="<?= htmlspecialchars($producto['nombre_producto']) ?>"
                                     onerror="this.src='https://via.placeholder.com/300?text=Imagen+No+Disponible'">
                            </div>
                            <div class="card-body">
                                <h6 class="product-title"><?= htmlspecialchars($producto['nombre_producto']) ?></h6>
                                <p class="product-brand"><?= htmlspecialchars($producto['marca']) ?></p>
                                
                                <!-- Precio mínimo en verde -->
                                <p class="product-price">$<?= number_format($producto['precio_minimo'], 2) ?></p>
                                
                                <!-- Tiendas disponibles -->
                                <div class="tiendas-disponibles">
                                    <small class="text-muted d-block">Disponible en:</small>
                                    <?php 
                                    $tiendas_array = explode(', ', $producto['tiendas_disponibles']);
                                    $tiendas_mostrar = array_slice($tiendas_array, 0, 2); // Mostrar solo 2 tiendas
                                    ?>
                                    <?php foreach ($tiendas_mostrar as $tienda_nombre): ?>
                                        <span class="badge tienda-badge"><?= htmlspecialchars($tienda_nombre) ?></span>
                                    <?php endforeach; ?>
                                    <?php if (count($tiendas_array) > 2): ?>
                                        <span class="badge tienda-badge">+<?= count($tiendas_array) - 2 ?> más</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="card-footer bg-white border-0 pt-0">
                                <a href="detalles.php?id=<?= $producto['id_producto'] ?>" class="btn btn-sm btn-success btn-details w-100">
                                    <i class="fas fa-info-circle me-1"></i> Detalles
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/favoritos.js" defer></script>
</body>
</html>