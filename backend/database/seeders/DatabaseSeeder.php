<?php

declare(strict_types=1);

namespace Database\Seeders;

use DateTimeImmutable;
use DateTimeZone;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use PDO;
use RuntimeException;
use Throwable;

final class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $pdo = null;
        try {
            if (!app()->environment('local')) {
                throw new RuntimeException('Demo podaci dopušteni su samo u lokalnom okruženju.');
            }
            $pdo = DB::connection()->getPdo();
            if ((int) $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn() !== 0) {
                echo "Korisnici već postoje. Demo unos je preskočen bez promjene podataka.\n";
                return;
            }
            foreach (['categories', 'vehicles', 'articles'] as $table) {
                if ((int) $pdo->query('SELECT COUNT(*) FROM ' . $table)->fetchColumn() !== 0) {
                    throw new RuntimeException('Demo unos zahtijeva prazne aplikacijske tablice.');
                }
            }

            $accountFile = storage_path('demo-accounts.json');
            if (is_file($accountFile)) {
                throw new RuntimeException('Datoteka demo računa već postoji. Sačuvajte je prije novog demo unosa.');
            }
            if (!is_dir(dirname($accountFile)) && !mkdir(dirname($accountFile), 0700, true)) {
                throw new RuntimeException('Nije moguće pripremiti lokalnu pohranu demo računa.');
            }

            $pdo->beginTransaction();
            $insert = static function (PDO $pdo, string $sql, array $values): int {
                $pdo->prepare($sql)->execute($values);
                return (int) $pdo->lastInsertId();
            };
            $roles = $pdo->query('SELECT code, id FROM roles')->fetchAll(PDO::FETCH_KEY_PAIR);
            $users = [];
            $accounts = [];
            foreach ([
                ['owner', 'Demo vlasnik', 'vlasnik@example.test', 'user', 1],
                ['other', 'Drugi demo vlasnik', 'drugi@example.test', 'user', 1],
                ['editor', 'Demo urednik', 'urednik@example.test', 'editor', 1],
                ['admin', 'Demo administrator', 'admin@example.test', 'admin', 1],
                ['inactive', 'Neaktivan demo račun', 'neaktivan@example.test', 'user', 0],
            ] as [$key, $name, $email, $role, $active]) {
                $password = bin2hex(random_bytes(12));
                $users[$key] = $insert(
                    $pdo,
                    'INSERT INTO users (role_id, name, email, password_hash, is_active) VALUES (?, ?, ?, ?, ?)',
                    [$roles[$role], $name, $email, password_hash($password, PASSWORD_DEFAULT), $active]
                );
                $accounts[] = ['email' => $email, 'password' => $password, 'role' => $role, 'active' => (bool) $active];
            }

            $categories = [];
            foreach (['Održavanje', 'Sigurnost', 'Električna vozila'] as $name) {
                $categories[] = $insert($pdo, 'INSERT INTO categories (name) VALUES (?)', [$name]);
            }
            $day = new DateTimeImmutable('today', new DateTimeZone('Europe/Zagreb'));
            foreach ([
                ['Priprema vozila za zimu', 'Provjerite gume, svjetla i tekućine.', $categories[0], 'published', $users['editor']],
                ['Sigurnost na mokroj cesti', 'Prilagodite brzinu uvjetima na cesti.', $categories[1], 'published', $users['admin']],
                ['Kako voditi evidenciju punjenja', 'Zabilježite energiju i cijenu punjenja.', $categories[2], 'published', $users['editor']],
                ['Demo nacrt — nije za javni prikaz', 'Ovaj zapis služi provjeri privatnosti nacrta.', $categories[0], 'draft', $users['editor']],
            ] as [$title, $summary, $category, $status, $author]) {
                $insert(
                    $pdo,
                    'INSERT INTO articles (author_id, category_id, title, summary, content_html, status, published_at) VALUES (?, ?, ?, ?, ?, ?, ?)',
                    [$author, $category, $title, $summary, '<p>' . $summary . '</p>', $status,
                        $status === 'published' ? gmdate('Y-m-d H:i:s', $day->modify('-2 days')->getTimestamp()) : null]
                );
            }

            $vehicles = [];
            foreach ([
                [$users['owner'], 'Gradski benzinac', 'Toyota', 'Yaris', 2018, 'petrol', 50000],
                [$users['owner'], 'Električni auto', 'Renault', 'Zoe', 2021, 'electricity', 20000],
                [$users['other'], 'Dizelski auto drugog vlasnika', 'Škoda', 'Octavia', 2017, 'diesel', 100000],
            ] as $values) {
                $vehicles[] = $insert($pdo, 'INSERT INTO vehicles (owner_id, name, make, model, model_year, energy_type, initial_odometer_km) VALUES (?, ?, ?, ?, ?, ?, ?)', $values);
            }
            $serviceDate = $day->modify('-25 days')->format('Y-m-d');
            $insert(
                $pdo,
                'INSERT INTO service_entries (vehicle_id, occurred_on, odometer_km, description, workshop, amount_eur) VALUES (?, ?, ?, ?, ?, ?)',
                [$vehicles[0], $serviceDate, 50500, 'Zamjena ulja i filtra', 'Demo radionica', '150.00']
            );
            $insert(
                $pdo,
                'INSERT INTO service_entries (vehicle_id, occurred_on, odometer_km, description, amount_eur) VALUES (?, ?, ?, ?, ?)',
                [$vehicles[1], $serviceDate, 20300, 'Pregled kočnica i filtra kabine', '80.00']
            );

            foreach ([
                [$vehicles[0], 'petrol', '-30 days', 50000, '35.000', '52.50', 1],
                [$vehicles[0], 'petrol', '-20 days', 50300, '10.000', '15.00', 0],
                [$vehicles[0], 'petrol', '-10 days', 50600, '26.000', '39.00', 1],
                [$vehicles[1], 'electricity', '-20 days', 20100, '24.000', '6.00', null],
                [$vehicles[1], 'electricity', '-5 days', 20300, '30.000', '9.00', null],
                [$vehicles[2], 'diesel', '-10 days', 100400, '40.000', '56.00', 1],
            ] as [$vehicle, $type, $offset, $odometer, $quantity, $amount, $full]) {
                $insert(
                    $pdo,
                    'INSERT INTO energy_entries (vehicle_id, energy_type, occurred_on, odometer_km, quantity, amount_eur, is_full_tank) VALUES (?, ?, ?, ?, ?, ?, ?)',
                    [$vehicle, $type, $day->modify($offset)->format('Y-m-d'), $odometer, $quantity, $amount, $full]
                );
            }
            $insert(
                $pdo,
                'INSERT INTO expenses (vehicle_id, occurred_on, category, description, amount_eur) VALUES (?, ?, ?, ?, ?)',
                [$vehicles[0], $serviceDate, 'insurance', 'Demo godišnje osiguranje', '240.00']
            );
            $insert(
                $pdo,
                'INSERT INTO expenses (vehicle_id, occurred_on, category, description, amount_eur) VALUES (?, ?, ?, ?, ?)',
                [$vehicles[1], $serviceDate, 'parking', 'Parkiranje', '5.00']
            );

            foreach ([
                [$vehicles[0], 'Registracija — uskoro', '+20 days', null, null],
                [$vehicles[0], 'Provjera guma — dospjelo', '-2 days', 50500, null],
                [$vehicles[1], 'Pregled pri 25.000 km', null, 25000, null],
                [$vehicles[1], 'Dovršen pregled', '-10 days', null, gmdate('Y-m-d H:i:s')],
            ] as [$vehicle, $title, $offset, $odometer, $completed]) {
                $insert(
                    $pdo,
                    'INSERT INTO reminders (vehicle_id, title, due_on, due_odometer_km, completed_at) VALUES (?, ?, ?, ?, ?)',
                    [$vehicle, $title, $offset === null ? null : $day->modify($offset)->format('Y-m-d'), $odometer, $completed]
                );
            }

            $json = json_encode($accounts, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            if (file_put_contents($accountFile, $json . PHP_EOL, LOCK_EX) === false) {
                throw new RuntimeException('Nije moguće zapisati lokalne demo račune.');
            }
            chmod($accountFile, 0600);
            $pdo->commit();
            echo "Demo podaci su spremljeni. Lozinke su samo u storage/demo-accounts.json (izvan Gita i javnog direktorija).\n";
        } catch (Throwable $exception) {
            if ($pdo instanceof PDO && $pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $exception;
        }
    }
}
