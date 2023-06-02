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

        // Forcer l'échec de l'enregistrement en utilisant une fausse méthode save
        $roomManagerMock = Mockery::mock(RoomManager::class)->makePartial();
        $roomManagerMock->shouldReceive('save')->andReturnUsing(function () {
            throw new \Exception('Erreur lors de l\'enregistrement du responsable de salle');
        });
        $this->app->instance(RoomManager::class, $roomManagerMock);

        $response = $this->postJson('/api/room-managers/store', [
            'name' => $name,
            'email' => $email,
        ]);

        $response->assertStatus(404)
            ->assertJson(['message' => 'Erreur lors de l\'enregistrement du responsable de salle']);
    }
}