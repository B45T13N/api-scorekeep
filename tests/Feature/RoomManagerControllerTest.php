<?php

namespace Tests\Feature;

use App\Models\Game;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoomManagerControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testStore()
    {
        Game::factory()->create();

        $name = 'John Doe';
        $email = 'johndoe@example.com';
        $gameId = 1;

        $response = $this->postJson('/api/room-managers/store', [
            'name' => $name,
            'email' => $email,
            'gameId' => $gameId
        ]);

        $response->assertStatus(201)
        ->assertJson(['message' => 'Responsable de salle enregistré avec succès']);

        $this->assertDatabaseHas('room_managers', [
            'name' => $name,
            'email' => $email,
            'gameId' => $gameId
        ]);
    }


    public function testStoreWithInvalidData()
    {
        $response = $this->postJson('/api/room-managers/store', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email']);
    }

}
