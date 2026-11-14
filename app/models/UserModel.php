<?php
/** Acceso a datos de usuarios. */
final class UserModel
{
    public function __construct(private PDO $db)
    {
    }

    public function findByEmail(string $email): ?array
    {
        $statement = $this->db->prepare('SELECT * FROM usuarios WHERE correo = :correo LIMIT 1');
        $statement->execute(['correo' => $email]);
        $user = $statement->fetch();
        return $user ?: null;
    }

    public function create(string $name, string $email, string $passwordHash): bool
    {
        $statement = $this->db->prepare(
            'INSERT INTO usuarios (nombre, correo, contrasena) VALUES (:nombre, :correo, :contrasena)'
        );
        return $statement->execute([
            'nombre' => $name,
            'correo' => $email,
            'contrasena' => $passwordHash,
        ]);
    }
}
