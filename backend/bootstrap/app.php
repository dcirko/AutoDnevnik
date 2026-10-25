<?php

declare(strict_types=1);

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__ . '/../routes/api.php',
        apiPrefix: 'api/v1',
        then: function (): void {
            Route::get('/', fn () => response()->json(
                ['data' => ['name' => 'AutoDnevnik API']],
                200,
                ['Cache-Control' => 'no-store']
            ));
        },
    )
    ->withMiddleware()
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(fn (Request $request): bool => true);
        $exceptions->render(function (Throwable $exception) {
            $status = $exception instanceof HttpExceptionInterface ? $exception->getStatusCode() : 500;
            [$code, $message] = match ($status) {
                404 => ['not_found', 'Traženi resurs ne postoji.'],
                405 => ['method_not_allowed', 'Metoda nije dopuštena.'],
                503 => ['service_unavailable', 'Usluga trenutačno nije dostupna.'],
                default => ['internal_error', 'Došlo je do pogreške pri obradi zahtjeva.'],
            };
            $headers = $exception instanceof HttpExceptionInterface ? $exception->getHeaders() : [];

            return response()->json(
                ['error' => ['code' => $code, 'message' => $message]],
                $status,
                array_merge($headers, ['Cache-Control' => 'no-store'])
            );
        });
    })->create();
