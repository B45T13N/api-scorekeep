<?php

namespace Tests\Feature;

use App\Models\Game;
use App\Models\LocalTeam;
use App\Models\VisitorTeam;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoomManagerControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testStore()
    {
        VisitorTeam::factory()->create();
        LocalTeam::factory()->create(['token'=>1234]);
        Game::factory()->create(["visitorTeamId" => 1]);

        $name = 'John Doe';
        $token = 1234;
        $gameId = 1;

        $response = $this->
        withHeaders([
            'Scorekeep-API-Key' => env('API_PUBLIC_KEY'),
        ])->
        postJson('/api/room-managers/store', [
            'name' => $name,
            'token' => $token,
            'gameId' => $gameId
        ]);

        $response->assertStatus(201)
        ->assertJson(['message' => 'Responsable de salle enregistré avec succès']);

        $this->assertDatabaseHas('room_managers', [
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
        postJson('/api/room-managers/store', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'token']);
    }

}
