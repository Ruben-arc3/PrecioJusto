<?php
include 'funciones.php';  // O require 'funciones.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PrecioJusto - Encuentra Los Mejores Precios</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!--script javascript-->
    <script src="animaciones.js"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="../css/styles.css">
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

    <!-- Hero Section -->
  <header class="hero-section bg-light py-5">
    <div class="container text-center py-4">
        <h1 class="display-4 fw-bold text-success mb-3">Compara y Ahorra en tus Productos</h1>
        <p class="lead mb-4">Encuentra los mejores precios y la información de tus productos favoritos</p>
        
        <div class="row justify-content-center">
            <div class="col-md-8">
                <form method="GET" action="" class="input-group mb-3 shadow-lg rounded-pill">
                    <input 
                        type="text" 
                        name="busqueda"
                        class="form-control form-control-lg border-success rounded-pill-start" 
                        placeholder="Buscar Producto..."
                        value="<?= htmlspecialchars($_GET['busqueda'] ?? '') ?>"
                    >
                    <button class="btn btn-success rounded-pill-end px-4" type="submit">
                        <i class="fas fa-search me-1"></i> Buscar
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>

    <!-- Sección de productos -->
<!-- Sección de productos -->
<!-- Sección de productos -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-5 fw-bold">Productos en Oferta</h2>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php foreach ($productos as $producto):
                $esFavorito = $usuarioAutenticado && in_array($producto['id_producto'], $favoritosUsuario);
            ?>
            <div class="col">
                <div class="card h-100 product-card-compact">
                    <div class="image-wrapper ratio ratio-1x1">
                        <img src="<?= htmlspecialchars($producto['Foto'] ?? 'https://via.placeholder.com/300x300?text=Producto') ?>"
                             class="card-img-top p-2"
                             alt="<?= htmlspecialchars($producto['nombre_producto']) ?>"
                             onerror="this.src='https://via.placeholder.com/300x300?text=Imagen+No+Disponible'">
                        <?php if($producto['precio'] < 2.00): ?>
                        <span class="position-absolute top-0 start-0 m-2 badge bg-danger">
                            <i class="fas fa-tag me-1"></i> Oferta
                        </span>
                        <?php endif; ?>
                    </div>

                    <div class="card-body pt-0">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="card-title mb-1"><?= htmlspecialchars($producto['nombre_producto']) ?></h5>
                                <p class="text-muted small mb-2"><?= htmlspecialchars($producto['marca']) ?></p>
                            </div>
                            <?php if ($usuarioAutenticado): ?>
                            <button
                                class="btn btn-sm btn-outline-secondary border-0 product-favorite"
                                data-id="<?= $producto['id_producto'] ?>"
                                data-accion="<?= $esFavorito ? 'quitar' : 'agregar' ?>"
                                title="<?= $esFavorito ? 'Quitar de favoritos' : 'Agregar a favoritos' ?>"
                            >
                                <i class="<?= $esFavorito ? 'fas' : 'far' ?> fa-heart text-danger"></i>
                            </button>
                            <?php else: ?>
                            <button class="btn btn-sm btn-outline-secondary border-0 product-favorite unauthenticated"
                                    title="Inicia sesión para agregar a favoritos">
                                <i class="far fa-heart"></i>
                            </button>
                            <?php endif; ?>
                        </div>

                        <div class="mb-2">
                            <div class="d-flex align-items-center">
                                <div class="text-warning small rating-stars" data-producto-id="<?= $producto['id_producto'] ?>">
                                    <?php
                                    $promedio = round($producto['promedio_valoracion'] ?? 0, 1);
                                    $cantidad = (int)($producto['cantidad_valoraciones'] ?? 0);
                                    ?>
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="fa-star <?= $promedio >= $i ? 'fas' : ($promedio >= $i - 0.5 ? 'fas fa-star-half-alt' : 'far') ?>"
                                           data-value="<?= $i ?>" role="button" style="cursor:pointer;"></i>
                                    <?php endfor; ?>
                                </div>
                                <span class="ms-1 small text-muted">(<?= $cantidad ?>)</span>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div>
                                <span class="h5 fw-bold text-success">$<?= number_format($producto['precio'], 2) ?></span>
                            </div>
                          <a href="detalles.php?id=<?= $producto['id_producto'] ?>" class="btn btn-outline-success btn-sm rounded-pill px-3 py-1">
                             <i class="fas fa-info-circle me-1"></i> Detalles
                            </a>

                        </div>
                    </div>

                    <div class="card-footer bg-white border-0 small text-muted">
                        <div class="d-flex justify-content-between">
                            <?php if (!empty($producto['nombre_tienda'])): ?>
                            <span title="<?= htmlspecialchars($producto['nombre_tienda']) ?>">
                                <i class="fas fa-store-alt me-1"></i>
                                <?= htmlspecialchars(mb_strimwidth($producto['nombre_tienda'], 0, 20, '...')) ?>
                            </span>
                            <?php else: ?>
                            <span><i class="fas fa-store-alt me-1"></i> Tienda no disponible</span>
                            <?php endif; ?>
                            <span><i class="fas fa-box me-1"></i> En stock</span>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="text-center mt-5">
            <a href="productos.php" class="btn btn-outline-success px-4">
                <i class="fas fa-arrow-down me-2"></i> Ver más productos
            </a>
        </div>
    </div>
</section>

<!-- Toast para iniciar sesión (favoritos) -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1100">
    <div id="toastLogin" class="toast align-items-center text-bg-info border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                <i class="fas fa-info-circle me-2"></i> Debes <a href="login.php" class="text-white fw-bold text-decoration-underline">iniciar sesión</a> para agregar a favoritos.
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<!-- Toast para mensajes generales (valoraciones) -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1100; max-width: 320px;">
  <div id="toastMensaje" class="toast align-items-center text-bg-info border-0" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body">
        <!-- Mensaje dinámico aquí -->
      </div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
  </div>
</div>


<script>
document.addEventListener("DOMContentLoaded", function () {
    // Toast para usuarios no autenticados en favoritos
    document.querySelectorAll('.product-favorite.unauthenticated').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const toast = new bootstrap.Toast(document.getElementById('toastLogin'));
            toast.show();
        });
    });

    // AJAX para favoritos
    document.querySelectorAll('.product-favorite:not(.unauthenticated)').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const productoId = this.dataset.id;
            const accion = this.dataset.accion;
            const icon = this.querySelector('i');
            const self = this;

            fetch(`favoritos_ajax.php?accion=${accion}&producto_id=${productoId}`)
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        if (data.action === 'agregado') {
                            icon.classList.remove('far');
                            icon.classList.add('fas');
                            self.dataset.accion = 'quitar';
                            self.setAttribute('title', 'Quitar de favoritos');
                        } else {
                            icon.classList.remove('fas');
                            icon.classList.add('far');
                            self.dataset.accion = 'agregar';
                            self.setAttribute('title', 'Agregar a favoritos');
                        }
                    }
                })
                .catch(err => console.error('Error:', err));
        });
    });

    // Función para mostrar toast genérico (valoraciones)
    function showToast(tipo, mensaje) {
        const toastElem = document.getElementById('toastMensaje');
        const toastBody = toastElem.querySelector('.toast-body');

        // Remover clases de color previas
        toastElem.classList.remove('text-bg-success', 'text-bg-info', 'text-bg-danger');
        toastElem.classList.add('toast', 'align-items-center', 'border-0', 'show');
        if (tipo === 'success') {
            toastElem.classList.add('text-bg-success');
        } else if (tipo === 'info') {
            toastElem.classList.add('text-bg-info');
        } else if (tipo === 'danger') {
            toastElem.classList.add('text-bg-danger');
        }

        toastBody.innerHTML = mensaje;

        const bsToast = new bootstrap.Toast(toastElem);
        bsToast.show();
    }

    // AJAX para valoraciones (estrellas)
    document.querySelectorAll('.rating-stars i').forEach(star => {
        star.addEventListener('click', function () {
            const productoId = this.parentElement.getAttribute('data-producto-id');
            const puntuacion = this.getAttribute('data-value');

            fetch(window.location.href, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({
                    accion: 'valorar',
                    producto_id: productoId,
                    puntuacion: puntuacion
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Actualizar visualmente las estrellas para ese producto
                    const estrellasContenedor = document.querySelector(`.rating-stars[data-producto-id="${productoId}"]`);
                    if (estrellasContenedor) {
                        estrellasContenedor.querySelectorAll('i').forEach((starIcon, idx) => {
                            if (idx < puntuacion) {
                                starIcon.classList.remove('far');
                                starIcon.classList.add('fas');
                                starIcon.classList.remove('fa-star-half-alt');
                            } else {
                                starIcon.classList.remove('fas');
                                starIcon.classList.add('far');
                                starIcon.classList.remove('fa-star-half-alt');
                            }
                        });
                    }
                    showToast('info', data.message || 'Error al valorar');
                }
            })
            .catch(() => {
                showToast('danger', 'Error en la conexión');
            });
        });
    });

});
</script>


<!-- Modal para login (solo se muestra si no está autenticado) -->


    <!-- Sección de Información Nutricional -->
  <section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5 fw-bold"><i class="fas fa-store-alt me-2"></i> Nuestras Tiendas</h2>
        
        <?php
        // Consulta para obtener todas las tiendas con sus URLs
        $query_tiendas = "SELECT id_tienda, nombre_tienda, url FROM tiendas ORDER BY nombre_tienda";
        $stmt_tiendas = $conn->prepare($query_tiendas);
        $stmt_tiendas->execute();
        $tiendas = $stmt_tiendas->fetchAll(PDO::FETCH_ASSOC);
        ?>
        
        <div class="row g-4">
            <?php foreach ($tiendas as $tienda): ?>
            <div class="col-md-6 col-lg-3">
                <a href="<?= htmlspecialchars($tienda['url'] ?? '#') ?>" class="text-decoration-none store-link">
                    <div class="card h-100 border-0 shadow-sm text-center p-4 store-card">
                        <div class="icon-circle bg-success text-white mx-auto mb-3">
                            <i class="fas fa-store"></i>
                        </div>
                        <h5 class="fw-bold"><?= htmlspecialchars($tienda['nombre_tienda']) ?></h5>
                        <div class="mt-2">
                            <span class="btn btn-sm btn-outline-success">
                                Visitar tienda <i class="fas fa-chevron-right ms-1"></i>
                            </span>
                        </div>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
        
        <?php if(count($tiendas) === 0): ?>
        <div class="text-center py-4">
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-circle me-2"></i> No hay tiendas registradas aún
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

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
                        <li class="mb-2"><a href="ofertas.php" class="text-white-50">Ofetas</a></li>
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

    

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Script personalizado -->
    <script src="../assets/js/script.js"></script>

   

    </body>
</html>