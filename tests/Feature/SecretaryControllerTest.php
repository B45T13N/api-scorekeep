<?php

namespace Tests\Feature;

use App\Models\Game;
use App\Models\LocalTeam;
use App\Models\VisitorTeam;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SecretaryControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testStore()
    {
        LocalTeam::factory()->create(['token'=>1234]);
        VisitorTeam::factory()->create();
        Game::factory()->create(["visitorTeamId" => 1]);

        $name = 'John Doe';
        $token = 1234;
        $gameId = 1;

        $response = $this->
        withHeaders([
            'Scorekeep-API-Key' => env('API_PUBLIC_KEY'),
        ])->
        postJson('/api/secretaries/store', [
            'name' => $name,
            'token' => $token,
            'gameId' => $gameId
        ]);

        $response->assertStatus(201)
            ->assertJson(['message' => 'Secrétaire enregistré avec succès']);

        $this->assertDatabaseHas('secretaries', [
            'name' => $name,
            'gameId' => $gameId
        ]);
    }


    public function testStoreWithInvalidData()
    {
        $response = $this->
        withHeaders([
            'Scorekeep-API-Key' => env('API_PUBLIC_KEY'),
        ])->
        postJson('/api/secretaries/store', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'token']);
    }
}
