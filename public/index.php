<?php

declare(strict_types=1);

use AutoDnevnik\Database\Connection;
use AutoDnevnik\Http\Response;

try {
    $config = require dirname(__DIR__) . '/src/bootstrap.php';
    $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
    $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

    if (!in_array($path, ['/', '/api/v1/health'], true)) {
        Response::error('not_found', 'Traženi resurs ne postoji.', 404);
        return;
    }
    if ($method !== 'GET') {
        Response::error('method_not_allowed', 'Metoda nije dopuštena.', 405, ['Allow' => 'GET']);
        return;
    }
    if ($path === '/') {
        header('Content-Type: text/html; charset=utf-8');
        header('Cache-Control: no-store');
        require dirname(__DIR__) . '/templates/start.php';
        return;
    }

    try {
        $pdo = Connection::open($config['database']);
        $pdo->query('SELECT id FROM roles LIMIT 1')->fetch();
        Response::json(['data' => ['status' => 'ok']]);
    } catch (Throwable $exception) {
        error_log('Database health check failed: ' . get_class($exception));
        Response::error('service_unavailable', 'Usluga trenutačno nije dostupna.', 503);
    }
} catch (Throwable $exception) {
    error_log('Application bootstrap failed: ' . get_class($exception));
    http_response_code(500);
    header('Content-Type: application/json; charset=utf-8');
    echo '{"error":{"code":"internal_error","message":"Došlo je do pogreške pri obradi zahtjeva."}}';
}
