<?php

namespace Tests\Feature;

use App\Models\LocalTeam;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LocalTeamControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testShow()
    {
        $localTeam = LocalTeam::factory()->create();

        $response = $this->
        withHeaders([
            'Scorekeep-API-Key' => env('API_PUBLIC_KEY'),
        ])->
        getJson('/api/local-teams/'.$localTeam->id);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'name' => $localTeam->name
                ]
            ]);
    }

//    public function testUpdate()
//    {
//
//        $localTeam = LocalTeam::factory()->create();
//
//        $updatedName = 'Updated Team';
//
//        $response = $this->
//        withHeaders([
//            'Scorekeep-API-Key' => env('API_PUBLIC_KEY'),
//        ])->
//        putJson('/api/local-teams/'.$localTeam->id, [
//            'name' => $updatedName,
//        ]);
//
//        $response->assertStatus(200)
//            ->assertJson(['message' => 'Equipe locale mise à jour avec succès']);
//
//        $this->assertDatabaseHas('local_teams', [
//            'id' => $localTeam->id,
//            'name' => $updatedName,
//        ]);
//    }
//
//    public function testUpdateWithInvalidData()
//    {
//
//        $localTeam = LocalTeam::factory()->create();
//
//        $response = $this->
//        withHeaders([
//            'Scorekeep-API-Key' => env('API_PUBLIC_KEY'),
//        ])->
//        putJson('/api/local-teams/'.$localTeam->id, []);
//
//        $response->assertStatus(422)
//            ->assertJsonValidationErrors(['name']);
//    }

    public function testShowNonExistingTeam()
    {
        $nonExistingTeamId = 999;

        $response = $this->
        withHeaders([
            'Scorekeep-API-Key' => env('API_PUBLIC_KEY'),
        ])->
        getJson('/api/local-teams/'.$nonExistingTeamId);

        $response->assertStatus(404)
            ->assertJson(['message' => 'Equipe visiteur non trouvée']);
    }

//    public function testUpdateNonExistingTeam()
//    {
//        $nonExistingTeamId = 999;
//
//        $response = $this->
//        withHeaders([
//            'Scorekeep-API-Key' => env('API_PUBLIC_KEY'),
//        ])->
//        putJson('/api/local-teams/'.$nonExistingTeamId, [
//            'name' => 'Updated Team',
//        ]);
//
//        $response->assertStatus(404)
//            ->assertJson(['message' => 'Equipe visiteur non trouvée']);
//    }

////    public function testStoreWithException()
////    {
////        $name = 'Avon';
////
////        // Forcer l'échec de l'enregistrement en utilisant une fausse méthode save
////        $localTeamMock = Mockery::mock(LocalTeam::class)->makePartial();
////        $localTeamMock->shouldReceive('save')->andReturnUsing(function () {
////            throw new \Exception('Erreur lors de l\'enregistrement du ');
////        });
////        $this->app->instance(LocalTeam::class, $localTeamMock);
////
////        $response = $this->
////        withHeaders([
////            'Scorekeep-API-Key' => env('API_PUBLIC_KEY'),
////        ])->
////        postJson('/api/local-teams/store', [
////            'name' => $name,
////        ]);
////
////        $response->assertStatus(404)
////            ->assertJson(['message' => 'Erreur lors de l\'enregistrement de l\'équipe locale']);
////    }
}
