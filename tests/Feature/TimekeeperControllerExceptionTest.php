<?php

namespace Tests\Feature;

use App\Models\Timekeeper;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Mockery;
use Tests\TestCase;

class TimekeeperControllerExceptionTest extends TestCase
{
    use DatabaseTransactions;

    public function testStoreWithException()
    {
        $name = 'John Doe';
        $email = 'johndoe@example.com';
        $gameId = 1;

        // Forcer l'échec de l'enregistrement en utilisant une fausse méthode save
        $roomManagerMock = Mockery::mock(Timekeeper::class)->makePartial();
        $roomManagerMock->shouldReceive('save')->andReturnUsing(function () {
            throw new \Exception('Erreur lors de l\'enregistrement du chronométreur');
        });
        $this->app->instance(Timekeeper::class, $roomManagerMock);

        $response = $this->postJson('/api/timekeepers/store', [
            'name' => $name,
            'email' => $email,
            'gameId' => $gameId
        ]);

        $response->assertStatus(404)
            ->assertJson(['message' => 'Erreur lors de l\'enregistrement du chronométreur']);
    }
}
