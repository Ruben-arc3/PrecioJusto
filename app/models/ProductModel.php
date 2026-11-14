<?php
/** Acceso a datos de productos usado por los controladores MVC. */
final class ProductModel
{
    public function __construct(private PDO $db)
    {
    }

    public function findById(int $id): ?array
    {
        $statement = $this->db->prepare('SELECT * FROM productos WHERE id_producto = :id');
        $statement->execute(['id' => $id]);
        $product = $statement->fetch();
        return $product ?: null;
    }

    public function findWithRating(int $id): ?array
    {
        $statement = $this->db->prepare(
            'SELECT p.*, COALESCE(ROUND(AVG(v.puntuacion), 1), 0) AS promedio_valoracion, '
            . 'COUNT(v.id_valoracion) AS cantidad_valoraciones '
            . 'FROM productos p LEFT JOIN valoraciones v ON p.id_producto = v.id_producto '
            . 'WHERE p.id_producto = :id GROUP BY p.id_producto, p.nombre_producto, p.Foto, p.marca, p.id_categoria'
        );
        $statement->execute(['id' => $id]);
        $product = $statement->fetch();
        return $product ?: null;
    }

    public function search(string $term, int $limit = 50): array
    {
        $statement = $this->db->prepare(
            'SELECT id_producto, nombre_producto, Foto, marca FROM productos '
            . 'WHERE nombre_producto LIKE :term OR marca LIKE :term '
            . 'ORDER BY nombre_producto LIMIT :limit'
        );
        $statement->bindValue(':term', '%' . $term . '%', PDO::PARAM_STR);
        $statement->bindValue(':limit', max(1, min($limit, 100)), PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll();
    }
}
