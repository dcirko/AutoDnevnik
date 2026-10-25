<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class () extends Migration {
    public function up(): void
    {
        $pdo = DB::connection()->getPdo();
        $sql = file_get_contents(database_path('schema.sql'));
        $checksum = hash('sha256', $sql);
        $tables = array_diff($pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN), ['migrations']);
        preg_match_all('/CREATE TABLE ([a-z_]+)/', $sql, $matches);
        $expectedTables = $matches[1];

        if ($tables !== []) {
            if (in_array('schema_versions', $tables, true)) {
                $statement = $pdo->prepare('SELECT checksum FROM schema_versions WHERE version = ?');
                $statement->execute(['001_initial']);
                if ($statement->fetchColumn() === $checksum && array_diff($expectedTables, $tables) === []) {
                    return;
                }
            }
            throw new RuntimeException('Baza nije prazna ili shema nije usklađena. Migracija ne briše postojeće tablice.');
        }

        foreach (explode(';', $sql) as $statement) {
            if (trim($statement) !== '') {
                $pdo->exec($statement);
            }
        }
        $statement = $pdo->prepare('INSERT INTO schema_versions (version, checksum) VALUES (?, ?)');
        $statement->execute(['001_initial', $checksum]);
    }

    public function down(): void
    {
        throw new RuntimeException('Početna preuzeta shema ne briše se rollbackom. Promjene uvodite novom migracijom.');
    }
};
