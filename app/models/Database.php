<?php
/**
 * Crea y configura la conexión PDO compartida por los modelos.
 * En producción, define DB_HOST, DB_NAME, DB_USER, DB_PASSWORD y opcionalmente DB_PORT,
 * o crea app/config/database.local.php a partir del archivo de ejemplo.
 */
final class Database
{
    private static ?PDO $connection = null;

    private function __construct()
    {
    }

    public static function connection(): PDO
    {
        if (self::$connection instanceof PDO) {
            return self::$connection;
        }

        $localConfigPath = __DIR__ . '/../config/database.local.php';
        $localConfig = is_file($localConfigPath) ? require $localConfigPath : [];
        if (!is_array($localConfig)) {
            throw new RuntimeException('La configuración local de base de datos debe retornar un arreglo.');
        }

        $host = getenv('DB_HOST') ?: ($localConfig['host'] ?? '');
        $name = getenv('DB_NAME') ?: ($localConfig['name'] ?? '');
        $user = getenv('DB_USER') ?: ($localConfig['user'] ?? '');
        $password = getenv('DB_PASSWORD');
        if ($password === false) {
            $password = $localConfig['password'] ?? '';
        }
        $port = getenv('DB_PORT');
        if ($port === false || $port === '') {
            $port = $localConfig['port'] ?? '';
        }

        if ($host === '' || $name === '' || $user === '') {
            throw new RuntimeException('Falta configurar DB_HOST, DB_NAME y DB_USER para conectar la base de datos.');
        }
        if ($port !== '' && !ctype_digit((string)$port)) {
            throw new RuntimeException('DB_PORT debe ser un número válido.');
        }

        $portPart = $port !== '' ? ";port={$port}" : '';
        $dsn = "mysql:host={$host}{$portPart};dbname={$name};charset=utf8mb4";
        self::$connection = new PDO($dsn, $user, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);

        return self::$connection;
    }
}