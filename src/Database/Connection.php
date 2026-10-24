<?php

declare(strict_types=1);

namespace AutoDnevnik\Database;

use PDO;
use RuntimeException;

final class Connection
{
    public static function open(array $config): PDO
    {
        if (!is_string($config['password']) || $config['password'] === '') {
            throw new RuntimeException('Database password is not configured.');
        }

        $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
            $config['host'], $config['port'], $config['name']);

        $pdo = new PDO($dsn, $config['username'], $config['password'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_TIMEOUT => 5,
            PDO::MYSQL_ATTR_MULTI_STATEMENTS => false,
        ]);
        $pdo->exec("SET time_zone = '+00:00'");

        return $pdo;
    }
}
