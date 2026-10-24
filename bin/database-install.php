<?php

declare(strict_types=1);

use AutoDnevnik\Database\Connection;

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

try {
    $config = require dirname(__DIR__) . '/src/bootstrap.php';
    $pdo = Connection::open($config['database']);
    $sql = file_get_contents(dirname(__DIR__) . '/database/schema.sql');
    $checksum = hash('sha256', $sql);
    $tables = $pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);
    preg_match_all('/CREATE TABLE ([a-z_]+)/', $sql, $matches);
    $expectedTables = $matches[1];

    if ($tables !== []) {
        if (in_array('schema_versions', $tables, true)) {
            $statement = $pdo->prepare('SELECT checksum FROM schema_versions WHERE version = ?');
            $statement->execute(['001_initial']);
            if ($statement->fetchColumn() === $checksum && array_diff($expectedTables, $tables) === []) {
                echo "Shema 001_initial već je primijenjena. Nema promjena.\n";
                exit(0);
            }
        }
        throw new RuntimeException('Baza nije prazna ili shema nije usklađena. Instalacija ne briše postojeće tablice.');
    }

    // This is our controlled schema, with no semicolons in literals or procedures.
    // MySQL DDL commits implicitly: do not pretend that these statements roll back.
    foreach (explode(';', $sql) as $statement) {
        if (trim($statement) !== '') {
            $pdo->exec($statement);
        }
    }
    $statement = $pdo->prepare('INSERT INTO schema_versions (version, checksum) VALUES (?, ?)');
    $statement->execute(['001_initial', $checksum]);
    echo "Shema 001_initial i tri korisničke uloge uspješno su postavljene.\n";
} catch (Throwable $exception) {
    fwrite(STDERR, 'Instalacija nije dovršena: ' . $exception->getMessage() . PHP_EOL);
    exit(1);
}
