<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Database\ConnectionInterface;
use PDOException;
use Tests\TestCase;

final class HealthTest extends TestCase
{
    public function testHealthReturnsSuccessWhenTheDatabaseCanBeRead(): void
    {
        $database = $this->createMock(ConnectionInterface::class);
        $database->expects($this->once())->method('select')
            ->with('SELECT id FROM roles LIMIT 1')->willReturn([]);
        $this->app->instance(ConnectionInterface::class, $database);

        $this->getJson('/api/v1/health')->assertOk()->assertExactJson(['data' => ['status' => 'ok']]);
    }

    public function testDatabaseFailureReturns503WithoutInternalDetailsEvenInDebugMode(): void
    {
        config(['app.debug' => true]);
        $database = $this->createMock(ConnectionInterface::class);
        $database->expects($this->once())->method('select')
            ->willThrowException(new PDOException('SQLSTATE DB_PASSWORD private-detail'));
        $this->app->instance(ConnectionInterface::class, $database);

        $this->getJson('/api/v1/health')->assertStatus(503)->assertExactJson([
            'error' => ['code' => 'service_unavailable', 'message' => 'Usluga trenutačno nije dostupna.'],
        ]);
    }

    public function testUnknownRouteReturnsTheExistingErrorFormat(): void
    {
        $this->get('/api/v1/missing')->assertNotFound()->assertExactJson([
            'error' => ['code' => 'not_found', 'message' => 'Traženi resurs ne postoji.'],
        ]);
    }

    public function testUnsupportedMethodReturns405WithAllowedMethods(): void
    {
        $this->postJson('/api/v1/health')->assertStatus(405)->assertHeader('Allow', 'GET, HEAD')
            ->assertExactJson([
                'error' => ['code' => 'method_not_allowed', 'message' => 'Metoda nije dopuštena.'],
            ]);
    }

    public function testUnexpectedFailureDoesNotExposeDetailsInDebugMode(): void
    {
        config(['app.debug' => true]);
        \Illuminate\Support\Facades\Route::get('/test-internal-error', function (): never {
            throw new \RuntimeException('private-detail DB_PASSWORD');
        });

        $this->getJson('/test-internal-error')->assertStatus(500)->assertExactJson([
            'error' => ['code' => 'internal_error', 'message' => 'Došlo je do pogreške pri obradi zahtjeva.'],
        ]);
    }
}
