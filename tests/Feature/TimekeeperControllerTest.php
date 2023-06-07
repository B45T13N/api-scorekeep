<?php

namespace Tests\Feature;

use App\Models\Game;
use App\Models\VisitorTeam;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TimekeeperControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testStore()
    {
        VisitorTeam::factory()->create();
        Game::factory()->create(["visitorTeamId" => 1]);

        $name = 'John Doe';
        $email = 'johndoe@example.com';
        $gameId = 1;

        $response = $this->
        withHeaders([
            'Scorekeep-API-Key' => env('API_PUBLIC_KEY'),
        ])->
        postJson('/api/timekeepers/store', [
            'name' => $name,
            'email' => $email,
            'gameId' => $gameId
        ]);

        $response->assertStatus(201)
            ->assertJson(['message' => 'Chronométreur enregistré avec succès']);

        $this->assertDatabaseHas('timekeepers', [
            'name' => $name,
            'email' => $email,
            'gameId' => $gameId
        ]);
    }


    public function testStoreWithInvalidData()
    {
        $response = $this->
        withHeaders([
            'Scorekeep-API-Key' => env('API_PUBLIC_KEY'),
        ])->
        postJson('/api/timekeepers/store', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email']);
    }
}
