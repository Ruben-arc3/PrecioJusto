<?php
include __DIR__ . '/../CatalogContext.php'; // O tu archivo de conexión a la base de datos

header('Content-Type: application/json');

$termino = $_GET['termino'] ?? '';

try {
    if (strlen($termino) >= 2) {
        $query = "SELECT nombre_producto as nombre FROM productos 
                 WHERE nombre_producto LIKE :termino 
                 ORDER BY nombre_producto LIMIT 10";
        $stmt = $conn->prepare($query);
        $stmt->execute(['termino' => '%' . $termino . '%']);
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        // Mostrar productos populares cuando el término es muy corto
        $query = "SELECT nombre_producto as nombre FROM productos 
                 ORDER BY RAND() LIMIT 5";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    echo json_encode($resultados);
} catch (PDOException $e) {
    echo json_encode([]);
}