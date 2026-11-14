<?php
/**
 * Adaptador temporal para las páginas existentes mientras migran a modelos.
 * Los controladores actuales esperan una variable global llamada $conn.
 */
require_once __DIR__ . '/Database.php';

try {
    $conn = Database::connection();
} catch (Throwable $e) {
    http_response_code(500);
    error_log('No se pudo conectar a la base de datos: ' . $e->getMessage());
    exit('No fue posible conectar con la base de datos.');
}
