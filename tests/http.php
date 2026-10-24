<?php

declare(strict_types=1);

$baseUrl = rtrim(getenv('TEST_BASE_URL') ?: 'http://localhost', '/');
$checks = [
    ['GET', '/', 200],
    ['GET', '/api/v1/health', 200],
    ['POST', '/api/v1/health', 405],
    ['GET', '/api/v1/not-found', 404],
    ['GET', '/.env', 403],
    ['GET', '/docs/PROJECT.md', 404],
    ['GET', '/storage/demo-accounts.json', 404],
    ['GET', '/bin/database-seed.php', 404],
];

try {
    foreach ($checks as [$method, $path, $expected]) {
        $curl = curl_init($baseUrl . $path);
        curl_setopt_array($curl, [
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => true,
            CURLOPT_CONNECTTIMEOUT => 3,
            CURLOPT_TIMEOUT => 10,
        ]);
        $response = curl_exec($curl);
        if ($response === false) {
            throw new RuntimeException('HTTP provjera nije mogla pristupiti web servisu.');
        }
        $status = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
        $headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $headers = substr($response, 0, $headerSize);
        $body = substr($response, $headerSize);
        curl_close($curl);
        if ($status !== $expected) {
            throw new RuntimeException("$method $path: očekivano $expected, dobiveno $status.");
        }
        if (str_starts_with($path, '/api/')) {
            $json = json_decode($body, true, 512, JSON_THROW_ON_ERROR);
            if (!preg_match('/^Content-Type: application\/json/mi', $headers)) {
                throw new RuntimeException('API ne vraća JSON Content-Type.');
            }
            if ($expected === 200 && ($json['data']['status'] ?? null) !== 'ok') {
                throw new RuntimeException('Nevaljan health odgovor.');
            }
            if ($expected >= 400 && !isset($json['error']['code'], $json['error']['message'])) {
                throw new RuntimeException('Nevaljan oblik API pogreške.');
            }
            if (preg_match('/SQLSTATE|mysql:host|DB_PASSWORD|\/var\/www/', $body)) {
                throw new RuntimeException('API odgovor otkriva interne podatke.');
            }
        }
        if ($expected === 405 && !preg_match('/^Allow: GET\r?$/mi', $headers)) {
            throw new RuntimeException('Odgovor 405 nema Allow zaglavlje.');
        }
        echo "OK: $method $path => $status\n";
    }
    echo count($checks), " HTTP provjera uspješno.\n";
} catch (Throwable $exception) {
    fwrite(STDERR, $exception->getMessage() . PHP_EOL);
    exit(1);
}
