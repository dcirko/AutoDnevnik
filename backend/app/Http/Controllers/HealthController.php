<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Database\ConnectionInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Throwable;

final class HealthController
{
    public function __invoke(ConnectionInterface $database): JsonResponse
    {
        try {
            $database->select('SELECT id FROM roles LIMIT 1');
        } catch (Throwable $exception) {
            Log::warning('Database health check failed.', ['exception' => get_class($exception)]);

            return response()->json(
                ['error' => ['code' => 'service_unavailable', 'message' => 'Usluga trenutačno nije dostupna.']],
                503,
                ['Cache-Control' => 'no-store']
            );
        }

        return response()->json(['data' => ['status' => 'ok']], 200, ['Cache-Control' => 'no-store']);
    }
}
