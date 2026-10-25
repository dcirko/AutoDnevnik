<?php

declare(strict_types=1);

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\DB;

$pdo = null;
$checks = 0;
try {
    require dirname(__DIR__) . '/vendor/autoload.php';
    $app = require dirname(__DIR__) . '/bootstrap/app.php';
    $app->make(Kernel::class)->bootstrap();
    if (!$app->environment('local', 'test', 'testing')) {
        throw new RuntimeException('Provjere baze dopuštene su samo lokalno i u testnom okruženju.');
    }
    $pdo = DB::connection()->getPdo();
    $pdo->beginTransaction();
    $run = static function (string $sql, array $values = []) use ($pdo): PDOStatement {
        $statement = $pdo->prepare($sql);
        $statement->execute($values);
        return $statement;
    };
    $check = static function (bool $condition, string $name) use (&$checks): void {
        if (!$condition) {
            throw new RuntimeException('FAIL: ' . $name);
        }
        $checks++;
        echo 'OK: ', $name, PHP_EOL;
    };
    $reject = static function (callable $operation, int $driverCode, string $name) use ($check): void {
        try {
            $operation();
        } catch (PDOException $exception) {
            $check(($exception->errorInfo[1] ?? null) === $driverCode, $name);
            return;
        }
        throw new RuntimeException('Očekivano odbijanje nije nastupilo: ' . $name);
    };

    $role = (int) $run("SELECT id FROM roles WHERE code = 'user'")->fetchColumn();
    $email = 'integration-' . bin2hex(random_bytes(6)) . '@example.test';
    $hash = password_hash('temporary-test-password', PASSWORD_DEFAULT);
    $run('INSERT INTO users (role_id, name, email, password_hash) VALUES (?, ?, ?, ?)', [$role, 'Test owner', $email, $hash]);
    $owner = (int) $pdo->lastInsertId();
    $reject(fn () => $run(
        'INSERT INTO users (role_id, name, email, password_hash) VALUES (?, ?, ?, ?)',
        [$role, 'Duplicate', strtoupper($email), $hash]
    ), 1062, 'E-mail je jedinstven neovisno o veličini slova');
    $reject(fn () => $run(
        'INSERT INTO users (role_id, name, email, password_hash, is_active) VALUES (?, ?, ?, ?, ?)',
        [$role, 'Invalid state', 'active-' . $email, $hash, 2]
    ), 3819, 'Aktivnost računa prihvaća samo 0 ili 1');

    $run(
        'INSERT INTO vehicles (owner_id, name, make, model, model_year, energy_type, initial_odometer_km) VALUES (?, ?, ?, ?, ?, ?, ?)',
        [$owner, 'Test petrol', 'Demo', 'P', 2020, 'petrol', 1000]
    );
    $vehicle = (int) $pdo->lastInsertId();
    $run(
        'INSERT INTO vehicles (owner_id, name, make, model, model_year, energy_type, initial_odometer_km) VALUES (?, ?, ?, ?, ?, ?, ?)',
        [$owner, 'Test electric', 'Demo', 'E', 2022, 'electricity', 1000]
    );
    $electric = (int) $pdo->lastInsertId();
    $reject(fn () => $run('DELETE FROM users WHERE id = ?', [$owner]), 1451, 'Vlasnik s vozilima ne može se fizički obrisati');
    $run('UPDATE users SET is_active = 0 WHERE id = ?', [$owner]);
    $check(
        (int) $run('SELECT COUNT(*) FROM vehicles WHERE owner_id = ?', [$owner])->fetchColumn() === 2,
        'Deaktivacija čuva postojeća vozila'
    );

    $serviceSql = 'INSERT INTO service_entries (vehicle_id, occurred_on, odometer_km, description, amount_eur) VALUES (?, ?, ?, ?, ?)';
    $run($serviceSql, [$vehicle, '2026-01-01', 1100, 'Test service', '100.10']);
    $reject(
        fn () => $run($serviceSql, [$vehicle, '2026-01-01', 1100, 'Negative cost', '-1.00']),
        3819,
        'Negativan iznos servisa odbijen je'
    );
    $reject(
        fn () => $run($serviceSql, [PHP_INT_MAX, '2026-01-01', 1100, 'Missing vehicle', '10.00']),
        1452,
        'Evidencija zahtijeva postojeće vozilo'
    );

    $energySql = 'INSERT INTO energy_entries (vehicle_id, energy_type, occurred_on, odometer_km, quantity, amount_eur, is_full_tank) VALUES (?, ?, ?, ?, ?, ?, ?)';
    $run($energySql, [$vehicle, 'petrol', '2026-01-01', 1000, '10.000', '20.20', 1]);
    $run($energySql, [$electric, 'electricity', '2026-01-01', 1000, '25.000', '7.50', null]);
    $reject(
        fn () => $run($energySql, [$electric, 'petrol', '2026-01-02', 1100, '10.000', '20.00', 1]),
        1452,
        'Benzinsko točenje ne može pripadati električnom vozilu'
    );
    $reject(
        fn () => $run($energySql, [$electric, 'electricity', '2026-01-02', 1100, '10.000', '2.00', 1]),
        3819,
        'Punjenje nema oznaku punog spremnika'
    );
    $reject(
        fn () => $run($energySql, [$vehicle, 'petrol', '2026-01-02', 1100, '0.000', '0.00', 0]),
        3819,
        'Količina energije mora biti pozitivna'
    );
    $reject(
        fn () => $run('UPDATE vehicles SET energy_type = ? WHERE id = ?', ['diesel', $vehicle]),
        1451,
        'Vrsta energije ne može promijeniti značenje postojećih evidencija'
    );

    $run(
        'INSERT INTO expenses (vehicle_id, occurred_on, category, description, amount_eur) VALUES (?, ?, ?, ?, ?)',
        [$vehicle, '2026-01-01', 'parking', 'Test parking', '30.30']
    );
    $total = $run('SELECT
        (SELECT COALESCE(SUM(amount_eur), 0) FROM service_entries WHERE vehicle_id = ?) +
        (SELECT COALESCE(SUM(amount_eur), 0) FROM energy_entries WHERE vehicle_id = ?) +
        (SELECT COALESCE(SUM(amount_eur), 0) FROM expenses WHERE vehicle_id = ?)', [$vehicle, $vehicle, $vehicle])->fetchColumn();
    $check($total === '150.60', 'Tri izvora troška zbrojena su jednom i točno decimalno');

    $run('INSERT INTO reminders (vehicle_id, title, due_odometer_km) VALUES (?, ?, ?)', [$vehicle, 'Test reminder', 2000]);
    $reject(
        fn () => $run('INSERT INTO reminders (vehicle_id, title) VALUES (?, ?)', [$vehicle, 'No deadline']),
        3819,
        'Podsjetnik mora imati datum ili kilometražu'
    );

    $run('INSERT INTO categories (name) VALUES (?)', ['Integration ' . $email]);
    $category = (int) $pdo->lastInsertId();
    $articleSql = 'INSERT INTO articles (author_id, category_id, title, summary, content_html, status, published_at) VALUES (?, ?, ?, ?, ?, ?, ?)';
    $run($articleSql, [$owner, $category, 'Draft', 'Summary', '<p>Text</p>', 'draft', null]);
    $article = (int) $pdo->lastInsertId();
    $reject(
        fn () => $run('DELETE FROM categories WHERE id = ?', [$category]),
        1451,
        'Kategorija s vijestima ne može se obrisati'
    );
    $reject(
        fn () => $run($articleSql, [$owner, $category, 'Invalid publication', 'Summary', 'Text', 'published', null]),
        3819,
        'Objavljena vijest mora imati vrijeme objave'
    );
    $run(
        'INSERT INTO article_images (article_id, storage_path, original_name, mime_type, size_bytes) VALUES (?, ?, ?, ?, ?)',
        [$article, 'test/' . $email . '.png', 'test.png', 'image/png', 123]
    );
    $run('DELETE FROM articles WHERE id = ?', [$article]);
    $check(
        (int) $run('SELECT COUNT(*) FROM article_images WHERE article_id = ?', [$article])->fetchColumn() === 0,
        'Brisanje vijesti uklanja metapodatke slike'
    );

    // The composite energy FK is RESTRICT to permit CHECK constraints in MySQL.
    // Vehicle deletion must remove energy entries first, in the same transaction.
    $reject(
        fn () => $run('DELETE FROM vehicles WHERE id = ?', [$vehicle]),
        1451,
        'Brisanje vozila s energijom zahtijeva izričitu obradu evidencija'
    );
    $run('DELETE FROM energy_entries WHERE vehicle_id = ?', [$vehicle]);
    $run('DELETE FROM vehicles WHERE id = ?', [$vehicle]);
    foreach (['service_entries', 'energy_entries', 'expenses', 'reminders'] as $table) {
        $check(
            (int) $run('SELECT COUNT(*) FROM ' . $table . ' WHERE vehicle_id = ?', [$vehicle])->fetchColumn() === 0,
            'Brisanje vozila: nema preostalih zapisa u ' . $table
        );
    }

    $pdo->rollBack();
    echo "Ukupno $checks provjera uspješno. Testni zapisi nisu trajno spremljeni.\n";
} catch (Throwable $exception) {
    if ($pdo instanceof PDO && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    fwrite(STDERR, $exception->getMessage() . PHP_EOL);
    exit(1);
}
