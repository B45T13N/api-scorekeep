<?php

namespace Tests\Feature;

use App\Models\Game;
use App\Models\VisitorTeam;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class VisitorTeamControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testShow()
    {
        Game::factory()->create();

        $visitorTeam = VisitorTeam::factory()->create(['gameId' => 1]);

        $response = $this->getJson('/api/visitor-teams/'.$visitorTeam->id);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'name' => $visitorTeam->name
                ]
            ]);
    }

    public function testUpdate()
    {
        Game::factory()->create();

        $visitorTeam = VisitorTeam::factory()->create(['gameId' => 1]);

        $updatedName = 'Updated Team';

        $response = $this->putJson('/api/visitor-teams/'.$visitorTeam->id, [
            'name' => $updatedName,
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Equipe visiteur mise à jour avec succès']);

        $this->assertDatabaseHas('visitor_teams', [
            'id' => $visitorTeam->id,
            'name' => $updatedName,
        ]);
    }

    public function testUpdateWithInvalidData()
    {
        Game::factory()->create();

        $visitorTeam = VisitorTeam::factory()->create(['gameId' => 1]);

        $response = $this->putJson('/api/visitor-teams/'.$visitorTeam->id, []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    public function testShowNonExistingTeam()
    {
        $nonExistingTeamId = 999;

        $response = $this->getJson('/api/visitor-teams/'.$nonExistingTeamId);

        $response->assertStatus(404)
            ->assertJson(['message' => 'Equipe visiteur non trouvée']);
    }

    public function testUpdateNonExistingTeam()
    {
        $nonExistingTeamId = 999;

        $response = $this->putJson('/api/visitor-teams/'.$nonExistingTeamId, [
            'name' => 'Updated Team',
        ]);

        $response->assertStatus(404)
            ->assertJson(['message' => 'Equipe visiteur non trouvée']);
    }
}
