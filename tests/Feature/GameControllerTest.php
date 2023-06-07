<?php

namespace Tests\Feature;

use App\Models\Game;
use App\Models\RoomManager;
use App\Models\Secretary;
use App\Models\Timekeeper;
use App\Models\VisitorTeam;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GameControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test index method.
     *
     * @return void
     */
    public function testIndex()
    {
        $this->withoutExceptionHandling();

        VisitorTeam::factory()->create();

        Game::factory(['visitorTeamId' => 1])->count(3)->create();

        $response = $this->
        withHeaders([
            'Scorekeep-API-Key' => env('API_PUBLIC_KEY'),
        ])->
        getJson(route('games.index'));

        $response->assertOk();
        $response->assertJsonCount(3, 'data');
    }

    /**
     * Test store method.
     *
     * @return void
     */
    public function testStore()
    {
        $this->withoutExceptionHandling();

        $visitorTeamName = $this->faker->name;
        $address = $this->faker->address;
        $category = $this->faker->word;
        $gameDate = Carbon::now()->addDay()->toDateTimeString();

        $response = $this->
        withHeaders([
            'Scorekeep-API-Key' => env('API_PUBLIC_KEY'),
        ])->
        postJson(route('games.store'), [
            'address' => $address,
            'category' => $category,
            'visitorTeamName' => $visitorTeamName,
            'gameDate' => $gameDate,
        ]);

        $response->assertCreated();
        $response->assertJson(['message' => 'Match enregistré avec succès']);

        $this->assertDatabaseHas('games', [
            'address' => $address,
            'category' => $category,
            'gameDate' => $gameDate,
        ]);

        $this->assertDatabaseHas('visitor_teams', [
            'name' => $visitorTeamName,
        ]);
    }

    /**
     * Test show method.
     *
     * @return void
     */
    public function testShow()
    {
        $this->withoutExceptionHandling();
        VisitorTeam::factory()->create();
        $game = Game::factory()->create(["visitorTeamId" => 1]);

        $response = $this->
        withHeaders([
            'Scorekeep-API-Key' => env('API_PUBLIC_KEY'),
        ])->
        getJson(route('games.show', ['gameId' => $game->id]));

        $response->assertOk();
        $response->assertJson(
            [
                'data' =>
                    [
                        "id" => $game->id,
                        "address" => $game->address,
                        "category" => $game->category,
                    ]
            ]
        );
    }

    /**
     * Test show method with non-existing game.
     *
     * @return void
     */
    public function testShowNonExistingGame()
    {
        $this->withoutExceptionHandling();

        $response = $this->
        withHeaders([
            'Scorekeep-API-Key' => env('API_PUBLIC_KEY'),
        ])->
        getJson(route('games.show', ['gameId' => 9999]));

        $response->assertNotFound();
        $response->assertJson([
            'message' => 'Match non trouvé',
        ]);
    }

    /**
     * Test update method.
     *
     * @return void
     */
    public function testUpdate()
    {
        $this->withoutExceptionHandling();
        VisitorTeam::factory()->create();

        $game = Game::factory()->create(["visitorTeamId" => 1]);

        Timekeeper::factory()->create();
        Secretary::factory()->create();
        RoomManager::factory()->create();

        $response = $this->
        withHeaders([
            'Scorekeep-API-Key' => env('API_PUBLIC_KEY'),
        ])->
        putJson(route('games.update', ['gameId' => $game->id]), [
            'timekeeperId' => 1,
            'secretaryId' => 1,
            'roomManagerId' => 1,
            'gameDate' => Carbon::now()->addDay()->toDateTimeString(),
        ]);

        $response->assertOk();
        $response->assertJson(['message' => 'Match mis à jour avec succès']);

        $this->assertDatabaseHas('games', [
            'id' => $game->id,
            'timekeeperId' => 1,
            'secretaryId' => 1,
            'roomManagerId' => 1,
        ]);
    }

    /**
     * Test update method with non-existing game.
     *
     * @return void
     */
    public function testUpdateNonExistingGame()
    {
        $this->withoutExceptionHandling();

        $response = $this->
        withHeaders([
            'Scorekeep-API-Key' => env('API_PUBLIC_KEY'),
        ])->
        putJson(route('games.update', ['gameId' => 9999]), [
            'timekeeperId' => 1,
            'secretaryId' => 2,
            'roomManagerId' => 3,
            'gameDate' => Carbon::now()->addDay()->toDateTimeString(),
        ]);

        $response->assertNotFound();
        $response->assertJson([
            'message' => 'Match non trouvé',
        ]);
    }

//    /**
//     * Test destroy method.
//     *
//     * @return void
//     */
//    public function testDestroy()
//    {
//        $this->withoutExceptionHandling();
//        VisitorTeam::factory()->create();
//
//        $game = Game::factory()->create(["visitorTeamId" => 1]);
//
//        $response = $this->deleteJson(route('games.destroy', ['gameId' => $game->id]));
//
//        $response->assertOk();
//        $response->assertJson(['message' => 'Match supprimé avec succès']);
//
//        $this->assertDatabaseMissing('games', [
//            'id' => $game->id,
//        ]);
//    }

//    /**
//     * Test destroy method with non-existing game.
//     *
//     * @return void
//     */
//    public function testDestroyNonExistingGame()
//    {
//        $this->withoutExceptionHandling();
//
//        $response = $this->deleteJson(route('games.destroy', ['gameId' => 9999]));
//
//        $response->assertNotFound();
//        $response->assertJson([
//            'message' => 'Match non trouvé',
//        ]);
//    }
}
