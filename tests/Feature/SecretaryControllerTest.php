<?php

namespace Tests\Feature;

use App\Models\Game;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SecretaryControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testStore()
    {
        Game::factory()->create();

        $name = 'John Doe';
        $email = 'johndoe@example.com';
        $gameId = 1;

        $response = $this->postJson('/api/secretaries/store', [
            'name' => $name,
            'email' => $email,
            'gameId' => $gameId
        ]);

        $response->assertStatus(201)
            ->assertJson(['message' => 'Secrétaire enregistré avec succès']);

        $this->assertDatabaseHas('secretaries', [
            'name' => $name,
            'email' => $email,
            'gameId' => $gameId
        ]);
    }


    public function testStoreWithInvalidData()
    {
        $response = $this->postJson('/api/secretaries/store', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email']);
    }
}
