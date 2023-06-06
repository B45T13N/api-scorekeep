<?php

namespace Tests\Feature;

use App\Models\RoomManager;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Mockery;
use Tests\TestCase;

class RoomManagerControllerExceptionTest extends TestCase
{
    use DatabaseTransactions;

    public function testStoreWithException()
    {
        $name = 'John Doe';
        $email = 'johndoe@example.com';
        $gameId = 1;

        // Forcer l'échec de l'enregistrement en utilisant une fausse méthode save
        $roomManagerMock = Mockery::mock(RoomManager::class)->makePartial();
        $roomManagerMock->shouldReceive('save')->andReturnUsing(function () {
            throw new \Exception('Erreur lors de l\'enregistrement du responsable de salle');
        });
        $this->app->instance(RoomManager::class, $roomManagerMock);

        $response = $this->
        withHeaders([
            'Scorekeep-API-Key' => env('API_PUBLIC_KEY'),
        ])->
        postJson('/api/room-managers/store', [
            'name' => $name,
            'email' => $email,
            'gameId' => $gameId
        ]);

        $response->assertStatus(404)
            ->assertJson(['message' => 'Erreur lors de l\'enregistrement du responsable de salle']);
    }
}
