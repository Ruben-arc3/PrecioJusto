<?php
require_once 'conexion.php';
require_once 'funciones.php';

// Verificar si el parámetro id existe, no está vacío y es numérico
if (!isset($_GET['id']) || empty($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
    header("Location: login.php");
    exit();
}

$id_producto = (int)$_GET['id'];

// Obtener información del producto
$stmt = $conn->prepare("
    SELECT p.*, p.url AS url_producto, t.nombre_tienda, t.url AS url_tienda,
           COALESCE(ROUND(AVG(v.puntuacion), 1), 0) AS promedio_valoracion,
           COUNT(v.id_valoracion) AS cantidad_valoraciones
    FROM productos p
    LEFT JOIN tiendas t ON p.id_tienda = t.id_tienda
    LEFT JOIN valoraciones v ON p.id_producto = v.id_producto
    WHERE p.id_producto = ?
    GROUP BY p.id_producto
");
$stmt->execute([$id_producto]);
$producto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$producto) {
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
    header("Location: productos.php?error=producto_no_encontrado");
    exit();
}

// Obtener favoritos del usuario si está logueado
$favoritosUsuario = [];
if (isset($_SESSION['usuario']['id'])) {
    $stmtFav = $conn->prepare("SELECT id_producto FROM favoritos WHERE id_usuario = ?");
    $stmtFav->execute([$_SESSION['usuario']['id']]);
    $favoritosUsuario = $stmtFav->fetchAll(PDO::FETCH_COLUMN);
}

// Obtener productos relacionados únicos
$relacionados = [];
$ids_agregados = [$id_producto];

function agregarProductos(&$relacionados, $productos, &$ids_agregados, $limite = 6) {
    foreach ($productos as $p) {
        if (count($relacionados) >= $limite) break;
        if (!in_array($p['id_producto'], $ids_agregados)) {
            $relacionados[] = $p;
            $ids_agregados[] = $p['id_producto'];
        }
    }
}

// Mismos tienda
$stmtRelacionados = $conn->prepare("
    SELECT id_producto, nombre_producto, precio, Foto, marca 
    FROM productos 
    WHERE id_tienda = ? AND id_producto != ?
    LIMIT 10
");
$stmtRelacionados->execute([$producto['id_tienda'], $id_producto]);
agregarProductos($relacionados, $stmtRelacionados->fetchAll(PDO::FETCH_ASSOC), $ids_agregados);

// Misma categoría
if (count($relacionados) < 6 && !empty($producto['categoria'])) {
    $stmtCategoria = $conn->prepare("
        SELECT id_producto, nombre_producto, precio, Foto, marca 
        FROM productos 
        WHERE categoria = ? AND id_producto != ?
        LIMIT 10
    ");
    $stmtCategoria->execute([$producto['categoria'], $id_producto]);
    agregarProductos($relacionados, $stmtCategoria->fetchAll(PDO::FETCH_ASSOC), $ids_agregados);
}

// Aleatorios
if (count($relacionados) < 6) {
    $stmtRandom = $conn->prepare("
        SELECT id_producto, nombre_producto, precio, Foto, marca 
        FROM productos 
        WHERE id_producto != ? 
        ORDER BY RAND() 
        LIMIT 10
    ");
    $stmtRandom->execute([$id_producto]);
    agregarProductos($relacionados, $stmtRandom->fetchAll(PDO::FETCH_ASSOC), $ids_agregados);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($producto['nombre_producto']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="animaciones.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/productos.css">
    <style>
        .star-rating .fa-star {
            font-size: 1.25rem;
            transition: color 0.2s;
        }
        .star-rating .fa-star.filled {
            color: #ffc107;
        }
        .star-rating .fa-star.empty {
            color: #e4e5e9;
        }
        .producto-card .card-img-top {
            object-fit: cover;
            height: 200px;
            border-radius: 1rem 1rem 0 0;
        }
        .producto-card .card {
            border-radius: 1rem;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .producto-card .card:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        .comentario-box {
            border-radius: 1rem;
            background-color: #f8f9fa;
        }
    </style>
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

<div class="container py-5">
    <div class="row">
        <div class="col-md-5">
            <img src="<?= htmlspecialchars($producto['Foto']) ?>" alt="<?= htmlspecialchars($producto['nombre_producto']) ?>"
                 class="img-fluid rounded border shadow-sm"
                 onerror="this.src='https://via.placeholder.com/500x500?text=Imagen+No+Disponible'">
        </div>
        <div class="col-md-7">
            <h2 class="fw-bold"><?= htmlspecialchars($producto['nombre_producto']) ?></h2>
            <p class="text-muted"><?= htmlspecialchars($producto['marca']) ?></p>
           
            <p><i class="fas fa-store me-1"></i> Comprar:
            <?php if (!empty($producto['url_producto'])): ?>
                <a href="<?= htmlspecialchars($producto['url_producto']) ?>" target="_blank">Aquí</a>
            <?php endif; ?>
            </p>

            <p><i class="fas fa-store me-1"></i> Tienda: 
                <a href="<?= htmlspecialchars($producto['url_tienda']) ?>" target="_blank">
                    <?= htmlspecialchars($producto['nombre_tienda']) ?>
                </a>
            </p>

            <h4 class="text-success">$<?= number_format($producto['precio'], 2) ?></h4>

            <div class="mb-2">
                <div class="d-flex align-items-center">
                    <div class="text-warning small rating-stars" data-producto-id="<?= $producto['id_producto'] ?>">
                        <?php
                        $promedio = round($producto['promedio_valoracion'] ?? 0, 1);
                        $cantidad = (int)($producto['cantidad_valoraciones'] ?? 0);
                        ?>
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <i class="fa-star <?= $promedio >= $i ? 'fas' : ($promedio >= $i - 0.5 ? 'fas fa-star-half-alt' : 'far') ?> me-1"
                               data-value="<?= $i ?>" role="button" style="cursor:pointer;"></i>
                        <?php endfor; ?>
                    </div>
                    <span class="ms-1 small text-muted">(<?= $cantidad ?>)</span>
                </div>
            </div>

            <?php if (isset($_SESSION['usuario'])): ?>
                <?php
                $esFavorito = in_array($producto['id_producto'], $favoritosUsuario);
                $accion = $esFavorito ? 'quitar' : 'agregar';
                $iconClass = $esFavorito ? 'fas' : 'far';
                $btnText = $esFavorito ? 'En favoritos' : 'Agregar a favoritos';
                ?>
                <button 
                    class="btn btn-sm product-favorite mt-3 border-0 shadow-sm"
                    data-id="<?= $producto['id_producto'] ?>"
                    data-accion="<?= $accion ?>"
                    title="<?= $btnText ?>"
                    style="background-color: <?= $esFavorito ? '#dc3545' : 'white' ?>; color: <?= $esFavorito ? 'white' : '#dc3545' ?>;"
                    onmouseover="this.style.backgroundColor='<?= $esFavorito ? '#c82333' : '#ffecec' ?>'"
                    onmouseout="this.style.backgroundColor='<?= $esFavorito ? '#dc3545' : 'white' ?>'">
                    <i class="<?= $iconClass ?> fa-heart me-2" style="color: <?= $esFavorito ? 'white' : '#dc3545' ?>"></i>
                    <span><?= $btnText ?></span>
                </button>
            <?php else: ?>
                <div class="alert alert-light mt-3 d-flex align-items-center border shadow-sm" style="border-color: #dc3545 !important;">
                    <i class="fas fa-info-circle me-2" style="color: #dc3545"></i>
                    <small class="text-muted">Inicia sesión para guardar favoritos</small>
                </div>
            <?php endif; ?>
        </div>
    </div>

   <!-- Productos relacionados -->
<?php if ($relacionados): ?>
    <hr class="my-5">
    <h4 class="mb-4"><i class="fas fa-thumbs-up me-2 text-success"></i>También te puede interesar</h4>
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4 mb-5">
        <?php foreach ($relacionados as $rel): ?>
            <div class="col">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="position-relative">
                        <img src="<?= htmlspecialchars($rel['Foto']) ?>"
                             class="card-img-top rounded-top"
                             alt="<?= htmlspecialchars($rel['nombre_producto']) ?>"
                             style="object-fit: cover; height: 220px;"
                             onerror="this.src='https://via.placeholder.com/300?text=Imagen+No+Disponible'">
                    </div>
                    <div class="card-body">
                        <h6 class="fw-bold text-truncate" title="<?= htmlspecialchars($rel['nombre_producto']) ?>">
                            <?= htmlspecialchars($rel['nombre_producto']) ?>
                        </h6>
                        <p class="text-muted small mb-1"><?= htmlspecialchars($rel['marca']) ?></p>
                        <h6 class="text-success fw-semibold">$<?= number_format($rel['precio'], 2) ?></h6>
                    </div>
                    <div class="card-footer bg-transparent border-0 pt-0">
                        <a href="detalles.php?id=<?= $rel['id_producto'] ?>" class="btn btn-outline-success w-100">
                            <i class="fas fa-info-circle me-1"></i> Ver Detalles
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- Sección de comentarios -->
<hr class="my-5">
<h4 class="mb-4"><i class="fas fa-comments me-2 text-primary"></i>Comentarios del producto</h4>
<div class="row">
    <div class="col-md-6">
        <!-- Caja del formulario de comentario -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <?php if (isset($_SESSION['usuario'])): ?>
                    <form id="form-comentario">
                        <input type="hidden" name="id_producto" value="<?= $id_producto ?>">
                        <div class="mb-3">
                            <label for="comentario" class="form-label fw-semibold">Escribe un comentario</label>
                            <textarea name="comentario" id="comentario" class="form-control rounded-3 shadow-sm" rows="3"
                                      placeholder="Tu opinión nos importa..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane me-1"></i> Publicar comentario
                        </button>
                    </form>
                <?php else: ?>
                    <div class="alert alert-info d-flex align-items-center shadow-sm mb-0">
                        <i class="fas fa-info-circle me-2 text-primary"></i>
                        <div>
                            <a href="login.php" class="btn btn-sm btn-primary me-2">Inicia sesión</a> para dejar tu comentario.
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Caja con scroll para comentarios -->
        <div id="comentarios-container" class="card shadow-sm border-0" style="max-height: 300px; overflow-y: auto;">
            <div class="card-body p-3">
                <!-- Comentarios se cargarán aquí vía AJAX -->
                <!-- Ejemplo estático (elimina esto si cargas dinámicamente): -->
                <!--
                <div class="mb-3 pb-2 border-bottom">
                    <div class="fw-semibold mb-1">Usuario Demo <span class="text-muted small">• hace 1 día</span></div>
                    <div class="text-muted">Muy buen producto, lo volvería a comprar.</div>
                </div>
                -->
            </div>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
// Función para cargar comentarios
function cargarComentarios() {
    $.ajax({
        url: 'obtener_comentarios.php',
        method: 'GET',
        data: { id_producto: <?= $id_producto ?> },
        success: function(response) {
            $('#comentarios-container').html(response);
            inicializarEventosComentarios();
        }
    });
}

// Inicializar eventos para editar/eliminar comentarios
function inicializarEventosComentarios() {
    // Botón editar comentario
    $('.btn-editar-comentario').click(function() {
        const idComentario = $(this).data('id');
        const comentarioBox = $(this).closest('.comentario-box');
        const contenido = comentarioBox.find('p').text().trim();
        
        // Crear formulario de edición
        const formEdicion = `
            <form class="form-edicion-comentario mt-3" data-id="${idComentario}">
                <textarea class="form-control mb-2" rows="3">${contenido}</textarea>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success btn-sm">Guardar</button>
                    <button type="button" class="btn btn-secondary btn-sm cancelar-edicion">Cancelar</button>
                </div>
            </form>
        `;
        
        // Ocultar contenido y botones, mostrar formulario
        comentarioBox.find('p, .btn-group').hide();
        comentarioBox.append(formEdicion);
    });
    
    // Cancelar edición
    $(document).on('click', '.cancelar-edicion', function() {
        const form = $(this).closest('.form-edicion-comentario');
        form.remove();
        form.closest('.comentario-box').find('p, .btn-group').show();
    });
    
    // Enviar edición
    $(document).on('submit', '.form-edicion-comentario', function(e) {
        e.preventDefault();
        const form = $(this);
        const idComentario = form.data('id');
        const nuevoContenido = form.find('textarea').val().trim();
        
        $.ajax({
            url: 'editar_comentario.php',
            method: 'POST',
            data: {
                id_comentario: idComentario,
                contenido: nuevoContenido
            },
            success: function(response) {
                if (response === 'ok') {
                    cargarComentarios();
                } else {
                    alert('Error al editar el comentario');
                }
            }
        });
    });
    
    // Eliminar comentario
    $('.btn-eliminar-comentario').click(function() {
        if (confirm('¿Estás seguro de eliminar este comentario?')) {
            const idComentario = $(this).data('id');
            
            $.ajax({
                url: 'eliminar_comentario.php',
                method: 'POST',
                data: { id_comentario: idComentario },
                success: function(response) {
                    if (response === 'ok') {
                        cargarComentarios();
                    } else {
                        alert('Error al eliminar el comentario');
                    }
                }
            });
        }
    });
}

// Cargar comentarios al inicio
$(document).ready(function() {
    cargarComentarios();
    
    // Enviar nuevo comentario
    $('#form-comentario').submit(function(e) {
        e.preventDefault();
        
        $.ajax({
            url: 'guardar_comentario.php',
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response === 'ok') {
                    cargarComentarios();
                    $('#form-comentario textarea').val('');
                } else {
                    alert('Error al guardar el comentario');
                }
            }
        });
    });
});
</script>

       
    </script>

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

<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll('.product-favorite').forEach(btn => {
        // Elimina posibles duplicaciones de eventos
        btn.replaceWith(btn.cloneNode(true));
    });

    document.querySelectorAll('.product-favorite').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault(); // Prevenir cualquier envío si accidentalmente hay un <form>

            const productoId = this.dataset.id;
            const accion = this.dataset.accion;
            const icon = this.querySelector('i');
            const span = this.querySelector('span');
            const self = this;

            fetch(`?accion=${accion}&producto_id=${productoId}&ajax=1`)
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        if (data.action === 'agregado') {
                            icon.classList.remove('far');
                            icon.classList.add('fas');
                            span.textContent = 'Quitar de favoritos';
                            self.dataset.accion = 'quitar';
                            self.setAttribute('title', 'Quitar de favoritos');
                        } else if (data.action === 'quitado') {
                            icon.classList.remove('fas');
                            icon.classList.add('far');
                            span.textContent = 'Agregar a favoritos';
                            self.dataset.accion = 'agregar';
                            self.setAttribute('title', 'Agregar a favoritos');
                        }
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(err => {
                    console.error('Error:', err);
                    alert('Error de conexión');
                });
        });
    });
});
</script>

<script>
document.querySelectorAll('.rating-stars').forEach(stars => {
    const input = document.getElementById('inputPuntuacion-' + stars.dataset.productoId);
    stars.querySelectorAll('i').forEach(star => {
        star.addEventListener('click', () => {
            const val = star.getAttribute('data-value');
            input.value = val;
            stars.querySelectorAll('i').forEach(s => {
                s.classList.remove('fas', 'far');
                s.classList.add(s.getAttribute('data-value') <= val ? 'fas' : 'far');
            });
        });
    });
});
</script>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Script personalizado -->
    <script src="../assets/js/script.js"></script>


</body>
</html>
