<?php

namespace Tests\Feature;

use App\Models\LocalTeam;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery;
use Tests\TestCase;

class LocalTeamControllerExceptionTest extends TestCase
{
    use DatabaseTransactions;

//    public function testStoreWithException()
//    {
//        $name = 'Avon';
//
//        // Forcer l'échec de l'enregistrement en utilisant une fausse méthode save
//        $localTeamMock = Mockery::mock(LocalTeam::class)->makePartial();
//        $localTeamMock->shouldReceive('save')->andReturnUsing(function () {
//            throw new \Exception('Erreur lors de l\'enregistrement du ');
//        });
//        $this->app->instance(LocalTeam::class, $localTeamMock);
//
//        $response = $this->
//        withHeaders([
//            'Scorekeep-API-Key' => env('API_PUBLIC_KEY'),
//        ])->
//        postJson('/api/local-teams/store', [
//            'name' => $name,
//        ]);
//
//        $response->assertStatus(404)
//            ->assertJson(['message' => 'Erreur lors de l\'enregistrement de l\'équipe locale']);
//    }
}
