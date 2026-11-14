<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($categoria) ?> | PrecioJusto</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../css/categorias-page.css">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-success sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">PrecioJusto</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="index.php"><i class="fas fa-home me-1"></i> Inicio</a></li>
                <li class="nav-item"><a class="nav-link" href="productos.php"><i class="fas fa-list me-1"></i> Productos</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-tags me-1"></i> Categorías
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="categorias.php?id=6">🍎 Frutas y Verduras</a></li>
                        <li><a class="dropdown-item" href="categorias.php?id=4">🧀 Lácteos y Huevos</a></li>
                        <li><a class="dropdown-item" href="categorias.php?id=8">🍞 Panadería</a></li>
                        <li><a class="dropdown-item" href="categorias.php?id=5">🥤 Bebidas</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="categorias.php?id=7">🌱 Orgánicos</a></li>
                    </ul>
                </li>
                <li class="nav-item"><a class="nav-link" href="ofertas.php"><i class="fas fa-tag"></i> Ofertas</a></li>
            </ul>
            <div class="d-flex">
                <?php if (isset($_SESSION['usuario'])): ?>
                    <div class="dropdown">
                        <button class="btn btn-light text-success fw-bold dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i> <?= htmlspecialchars($_SESSION['usuario']['nombre']) ?>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                             <li><a class="dropdown-item" href="/ProyectoDes/app/views/perfil.php"><i class="fas fa-user me-2"></i> Mi Perfil</a></li>

                            <li><a class="dropdown-item" href="mostrar_favoritos.php"><i class="fas fa-heart me-2"></i> Favoritos</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión</a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a href="login.php" class="btn btn-light text-success fw-bold">
                        <i class="fas fa-user me-1"></i> Iniciar Sesión
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<!-- CONTENIDO DE LA CATEGORÍA -->
<div class="container py-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h2 class="fw-bold mb-0"><?= htmlspecialchars($categoria) ?></h2>
        </div>
        <div class="col-md-6">
            <form class="d-flex" method="GET" action="categorias.php">
                <input type="hidden" name="id" value="<?= $id_categoria ?>">
                <input type="text" name="busqueda" class="form-control me-2" placeholder="Buscar productos..." value="<?= htmlspecialchars($busqueda) ?>">
                <select name="orden" class="form-select me-2">
                    <option value="nombre_producto ASC" <?= $orden == 'nombre_producto ASC' ? 'selected' : '' ?>>Nombre A-Z</option>
                    <option value="nombre_producto DESC" <?= $orden == 'nombre_producto DESC' ? 'selected' : '' ?>>Nombre Z-A</option>
                    <option value="precio_minimo ASC" <?= $orden == 'precio_minimo ASC' ? 'selected' : '' ?>>Precio menor</option>
                    <option value="precio_minimo DESC" <?= $orden == 'precio_minimo DESC' ? 'selected' : '' ?>>Precio mayor</option>
                </select>
                <button class="btn btn-success" type="submit"><i class="fas fa-filter me-1"></i> Filtrar</button>
            </form>
        </div>
    </div>

    <?php if (empty($productos)): ?>
        <div class="alert alert-info text-center py-5">
            <i class="fas fa-info-circle fa-3x mb-3"></i>
            <h3>No se encontraron productos</h3>
            <p class="mb-0">Prueba otra búsqueda o cambia el orden</p>
        </div>
    <?php else: ?>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
            <?php foreach ($productos as $producto): ?>
                <div class="col">
                    <div class="card product-card h-100">
                        <div class="card-img-container">
                            <img src="<?= htmlspecialchars($producto['Foto']) ?>" class="card-img-top" alt="<?= htmlspecialchars($producto['nombre_producto']) ?>" onerror="this.src='https://via.placeholder.com/300?text=Imagen+No+Disponible'">
                        </div>
                        <div class="card-body">
                            <h6 class="product-title"><?= htmlspecialchars($producto['nombre_producto']) ?></h6>
                            <p class="product-brand mb-1"><?= htmlspecialchars($producto['marca']) ?></p>
                            <p class="product-price mb-0">$<?= number_format($producto['precio_minimo'], 2) ?></p>
                            
                            <?php if (!empty($producto['tiendas'])): ?>
                                <div class="product-stores mt-2">
                                    <small class="text-muted">Disponible en:</small><br>
                                    <?php 
                                    $tiendas = explode(', ', $producto['tiendas']);
                                    foreach ($tiendas as $tienda): 
                                        if (!empty(trim($tienda))):
                                    ?>
                                        <span class="store-badge"><?= htmlspecialchars($tienda) ?></span>
                                    <?php 
                                        endif;
                                    endforeach; 
                                    ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="card-footer bg-white">
                            <a href="detalles.php?id=<?= $producto['id_producto'] ?>" class="btn btn-sm btn-success w-100">
                                <i class="fas fa-shopping-cart me-1"></i> Ver Detalles
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>