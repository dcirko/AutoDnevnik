<?php

declare(strict_types=1);

namespace AutoDnevnik\Http;

final class Response
{
    public static function json(array $body, int $status = 200, array $headers = []): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-store');
        foreach ($headers as $name => $value) {
            header($name . ': ' . $value);
        }
        echo json_encode($body, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);
    }

    public static function error(string $code, string $message, int $status, array $headers = []): void
    {
        self::json(['error' => ['code' => $code, 'message' => $message]], $status, $headers);
    }
}
