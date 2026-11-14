<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Catálogo de Productos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/productos.css">
    <link rel="stylesheet" href="../css/catalogo.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-success sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">
               PrecioJusto
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php"><i class="fas fa-home me-1"></i> Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="productos.php"><i class="fas fa-list me-1"></i> Productos</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-tags"></i> Categorías
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="categorias.php?id=6">🍎 Frutas y Verduras</a></li>
                            <li><a class="dropdown-item" href="categorias.php?id=4">🧀 Lácteos y Huevos</a></li>
                            <li><a class="dropdown-item" href="categorias.php?id=8">🍞 Panadería</a></li>
                            <li><a class="dropdown-item" href="categorias.php?id=5">🥤 Bebidas</a></li>
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

    <div class="container py-4">
        <div class="row mb-4">
            <div class="col-md-6">
                <h2 class="fw-bold">Catálogo de Productos</h2>
                <p class="text-muted">Mostrando <?= count($productos) ?> de <?= $totalProductos ?> productos</p>
            </div>
            <div class="col-md-6">
                <form class="d-flex" action="productos.php" method="get">
                    <input type="text" name="busqueda" class="form-control me-2" 
                           placeholder="Buscar productos..." value="<?= htmlspecialchars($busqueda) ?>"
                           list="sugerenciasProductos"
                           id="buscadorProductos"
                           autocomplete="off">
                    <datalist id="sugerenciasProductos">
                        <!-- Las sugerencias se llenarán con JavaScript -->
                    </datalist>
                    <input type="hidden" name="tienda" value="<?= $tienda ?>">
                    <button class="btn btn-success" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3 mb-4">
                <div class="card filter-card">
                    <div class="card-header bg-success text-white">
                        <i class="fas fa-filter me-2"></i>Filtrar y Ordenar
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Tiendas</h5>
                        <div class="tienda-filter mb-3">
                            <select class="form-select" onchange="location = this.value;">
                                <option value="productos.php?<?= http_build_query(array_merge($_GET, ['tienda' => 0, 'pagina' => 1])) ?>" <?= $tienda == 0 ? 'selected' : '' ?>>
                                    Todas las tiendas
                                </option>
                                <?php foreach ($tiendas as $t): ?>
                                    <option value="productos.php?<?= http_build_query(array_merge($_GET, ['tienda' => $t['id_tienda'], 'pagina' => 1])) ?>" <?= $tienda == $t['id_tienda'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($t['nombre_tienda']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <h5 class="card-title">Ordenar por</h5>
                        <div class="d-grid gap-2">
                            <a href="productos.php?<?= http_build_query(array_merge($_GET, ['orden' => 'recientes', 'pagina' => 1])) ?>" 
                               class="btn btn-sm btn-outline-success text-start <?= $orden == 'recientes' ? 'active' : '' ?>">
                                <i class="fas fa-clock me-1"></i> Más recientes
                            </a>
                            <a href="productos.php?<?= http_build_query(array_merge($_GET, ['orden' => 'precio_asc', 'pagina' => 1])) ?>" 
                               class="btn btn-sm btn-outline-success text-start <?= $orden == 'precio_asc' ? 'active' : '' ?>">
                                <i class="fas fa-sort-amount-down me-1"></i> Precio: menor a mayor
                            </a>
                            <a href="productos.php?<?= http_build_query(array_merge($_GET, ['orden' => 'precio_desc', 'pagina' => 1])) ?>" 
                               class="btn btn-sm btn-outline-success text-start <?= $orden == 'precio_desc' ? 'active' : '' ?>">
                                <i class="fas fa-sort-amount-up me-1"></i> Precio: mayor a menor
                            </a>
                            <a href="productos.php?<?= http_build_query(array_merge($_GET, ['orden' => 'nombre_asc', 'pagina' => 1])) ?>" 
                               class="btn btn-sm btn-outline-success text-start <?= $orden == 'nombre_asc' ? 'active' : '' ?>">
                                <i class="fas fa-sort-alpha-down me-1"></i> Nombre: A-Z
                            </a>
                            <a href="productos.php?<?= http_build_query(array_merge($_GET, ['orden' => 'nombre_desc', 'pagina' => 1])) ?>" 
                               class="btn btn-sm btn-outline-success text-start <?= $orden == 'nombre_desc' ? 'active' : '' ?>">
                                <i class="fas fa-sort-alpha-up me-1"></i> Nombre: Z-A
                            </a>
                        </div>
                    </div>
                </div>
            </div>

           <div class="col-md-9">
    <?php if (empty($productos)): ?>
        <div class="alert alert-info text-center py-5">
            <i class="fas fa-info-circle fa-3x mb-3"></i>
            <h3>No se encontraron productos</h3>
            <p class="mb-0">Intenta con otros términos de búsqueda o filtros</p>
            <a href="productos.php" class="btn btn-success mt-3">Ver todos los productos</a>
        </div>
    <?php else: ?>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
            <?php foreach ($productos as $producto): ?>
                <div class="col">
                    <div class="card product-card h-100">
                        <div class="card-img-container">
                            <img src="<?= htmlspecialchars($producto['Foto']) ?>" 
                                 class="card-img-top" 
                                 alt="<?= htmlspecialchars($producto['nombre_producto']) ?>"
                                 onerror="this.src='https://via.placeholder.com/300?text=Imagen+No+Disponible'">
                        </div>
                        <div class="card-body">
                            <h6 class="product-title"><?= htmlspecialchars($producto['nombre_producto']) ?></h6>
                            <p class="product-brand mb-1"><?= htmlspecialchars($producto['marca']) ?></p>
                            
                            <!-- Mostrar todas las tiendas disponibles -->
                            <div class="tiendas-disponibles mb-2">
                                <small class="text-muted d-block">Disponible en:</small>
                                <?php 
                                $tiendas_array = explode(', ', $producto['tiendas_disponibles']);
                                $tiendas_mostrar = array_slice($tiendas_array, 0, 3);
                                ?>
                                <?php foreach ($tiendas_mostrar as $tienda_nombre): ?>
                                    <span class="badge bg-light text-dark mb-1"><?= htmlspecialchars($tienda_nombre) ?></span>
                                <?php endforeach; ?>
                                <?php if (count($tiendas_array) > 3): ?>
                                    <span class="badge bg-light text-dark">+<?= count($tiendas_array) - 3 ?> más</span>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Precio en verde -->
                            <p class="product-price text-success fw-bold mb-0">
                                $<?= number_format($producto['precio_minimo'], 2) ?>
                            </p>
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

        <!-- Paginación (se mantiene igual) -->
        <?php if ($totalPaginas > 1): ?>
            <nav class="mt-4">
                <ul class="pagination justify-content-center">
                    <?php if ($pagina > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="productos.php?<?= http_build_query(array_merge($_GET, ['pagina' => $pagina - 1])) ?>">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php 
                    $inicio = max(1, $pagina - 2);
                    $fin = min($totalPaginas, $pagina + 2);
                    
                    if ($inicio > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="productos.php?<?= http_build_query(array_merge($_GET, ['pagina' => 1])) ?>">1</a>
                        </li>
                        <?php if ($inicio > 2): ?>
                            <li class="page-item disabled">
                                <span class="page-link">...</span>
                            </li>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php for ($i = $inicio; $i <= $fin; $i++): ?>
                        <li class="page-item <?= $i == $pagina ? 'active' : '' ?>">
                            <a class="page-link" href="productos.php?<?= http_build_query(array_merge($_GET, ['pagina' => $i])) ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($fin < $totalPaginas): ?>
                        <?php if ($fin < $totalPaginas - 1): ?>
                            <li class="page-item disabled">
                                <span class="page-link">...</span>
                            </li>
                        <?php endif; ?>
                        <li class="page-item">
                            <a class="page-link" href="productos.php?<?= http_build_query(array_merge($_GET, ['pagina' => $totalPaginas])) ?>"><?= $totalPaginas ?></a>
                        </li>
                    <?php endif; ?>

                    <?php if ($pagina < $totalPaginas): ?>
                        <li class="page-item">
                            <a class="page-link" href="productos.php?<?= http_build_query(array_merge($_GET, ['pagina' => $pagina + 1])) ?>">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        <?php endif; ?>
    <?php endif; ?>
</div>
        </div>
    </div>
    

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/catalogo.js" defer></script>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5 class="fw-bold mb-3"> PrecioJusto</h5>
                    <p>La mejor herramienta para comparar precios de tus alimentos favoritos.</p>
                </div>
                <div class="col-md-2 mb-4 mb-md-0">
                    <h5 class="fw-bold mb-3">Enlaces</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="index.php" class="text-white-50">Inicio</a></li>
                        <li class="mb-2"><a href="productos.php" class="text-white-50">Productos</a></li>
                        <li class="mb-2"><a href="index.php" class="text-white-50">Categorías</a></li>
                        <li class="mb-2"><a href="ofertas.php" class="text-white-50">Ofertas</a></li>
                    </ul>
                </div>
                <div class="col-md-3 mb-4 mb-md-0">
                    <h5 class="fw-bold mb-3">Contacto</h5>
                    <ul class="list-unstyled text-white-50">
                        <li class="mb-2"><i class="fas fa-envelope me-2"></i> info@preciojusto.com</li>
                        <li class="mb-2"><i class="fas fa-phone me-2"></i> +57 321 879 828</li>
                        <li class="mb-2"><i class="fas fa-map-marker-alt me-2"></i> Monteria, Cordoba</li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5 class="fw-bold mb-3">Síguenos</h5>
                    <div class="social-icons">
                        <a href="#" class="text-white me-3"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
            <hr class="my-4 bg-secondary">
            <div class="text-center text-white-50 small">
                <p class="mb-0">&copy; 2025 PrecioJusto. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>
</body>
</html>