<?php
// Soporta la estructura local app/ y la estructura aplanada de htdocs en InfinityFree.
if (is_file(__DIR__ . '/views/index.php')) {
    header('Location: views/index.php');
    exit;
}

if (is_file(__DIR__ . '/app/views/index.php')) {
    header('Location: app/views/index.php');
    exit;
}

http_response_code(404);
echo 'No se encontró la entrada de PrecioJusto. Verifica que la carpeta views esté dentro de htdocs.';