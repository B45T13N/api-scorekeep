<?php

namespace Tests\Feature;

use App\Models\Game;
use App\Models\LocalTeam;
use App\Models\User;
use App\Models\VisitorTeam;
use App\Models\Volunteer;
use App\Models\VolunteerType;
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
        VisitorTeam::factory()->create();

        Game::factory(['visitorTeamId' => 1, 'gameDate' => Carbon::tomorrow("Europe/Paris")->toDateTimeString()])->count(3)->create();
        $today = Carbon::now("Europe/Paris")->format("Y-m-d");
        $parameters = [
            'local_team_id' => 1,
            'start_date' => $today,
            'end_date' => Carbon::now()->addWeek()->format("Y-m-d"),
        ];
        $response = $this->
        withHeaders([
            'Scorekeep-API-Key' => env('API_PUBLIC_KEY'),
        ])
            ->getJson(route('games.index') . '?' . http_build_query($parameters));

        $response->assertOk();
        $response->assertJsonCount(3, 'data');
    }

    /**
     * Test weekGames method.
     *
     * @return void
     */
    public function testWeekGames()
    {
        VisitorTeam::factory()->create();

        Game::factory(['visitorTeamId' => 1, 'gameDate' => Carbon::tomorrow("Europe/Paris")->toDateTimeString()])->count(3)->create();
        $parameters = [
            'local_team_id' => 1,
        ];
        $response = $this->
        withHeaders([
            'Scorekeep-API-Key' => env('API_PUBLIC_KEY'),
        ])
            ->getJson(route('games.weekGames') . '?' . http_build_query($parameters));

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
        LocalTeam::factory()->create();

        $visitorTeamName = $this->faker->name;
        $address = $this->faker->address;
        $category = $this->faker->word;
        $gameDate = Carbon::now()->addDay()->toDateTimeString();

        $user = User::factory()->create(
            [
                "email" => "email@mail.com",
                "password" => "password"
            ]
        );

        $response = $this->
        withHeaders([
            'Scorekeep-API-Key' => env('API_PUBLIC_KEY'),
        ])->postJson(route('api.login'), [
            'email' => "email@mail.com",
            'password' => "password",
        ]);

        $response = $this->
        withHeaders([
            'Scorekeep-API-Key' => env('API_PUBLIC_KEY'),
        ])->
        postJson(route('games.store'), [
            'localTeamId' => 1,
            'address' => $address,
            'category' => $category,
            'visitorTeamName' => $visitorTeamName,
            'gameDate' => $gameDate,
            'isHomeMatch' => true,
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

        $user = User::factory()->create(
            [
                "email" => "email@mail.com",
                "password" => "password"
            ]
        );

        $response = $this->
        withHeaders([
            'Scorekeep-API-Key' => env('API_PUBLIC_KEY'),
        ])->postJson(route('api.login'), [
            'email' => "email@mail.com",
            'password' => "password",
        ]);

        $game = Game::factory()->create(["visitorTeamId" => 1]);
        $date = Carbon::now()->addDay()->toDateTimeString();
        $address = "14 rue du temple 75000 Paris";
        $response = $this->
        withHeaders([
            'Scorekeep-API-Key' => env('API_PUBLIC_KEY'),
        ])->
        putJson(route('games.update', ['gameId' => $game->id]), [
            'gameDate' => $date,
            'address' => $address
        ]);

        $response->assertOk();
        $response->assertJson(['message' => 'Match mis à jour avec succès']);

        $this->assertDatabaseHas('games', [
            'id' => $game->id,
            'address' => $address,
            'gameDate' => $date
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

        $user = User::factory()->create(
            [
                "email" => "email@mail.com",
                "password" => "password"
            ]
        );

        $response = $this->
        withHeaders([
            'Scorekeep-API-Key' => env('API_PUBLIC_KEY'),
        ])->postJson(route('api.login'), [
            'email' => "email@mail.com",
            'password' => "password",
        ]);

        $response = $this->
        withHeaders([
            'Scorekeep-API-Key' => env('API_PUBLIC_KEY'),
        ])->
        putJson(route('games.update', ['gameId' => 9999]), [
            'gameDate' => Carbon::now()->addDay()->toDateTimeString(),
        ]);

        $response->assertNotFound();
        $response->assertJson([
            'message' => 'Match non trouvé',
        ]);
    }

    /**
     * Test add volunteers method.
     *
     * @return void
     */
    public function testAddVolunteers()
    {
        $this->withoutExceptionHandling();
        VisitorTeam::factory()->create();

        $user = User::factory()->create(
            [
                "email" => "email@mail.com",
                "password" => "password"
            ]
        );

        $response = $this->
        withHeaders([
            'Scorekeep-API-Key' => env('API_PUBLIC_KEY'),
        ])->postJson(route('api.login'), [
            'email' => "email@mail.com",
            'password' => "password",
        ]);

        $game = Game::factory()->create(["visitorTeamId" => 1]);

        VolunteerType::factory()->create();
        VolunteerType::factory()->create();
        VolunteerType::factory()->create();
        VolunteerType::factory()->create();
        Volunteer::factory()->create(['volunteerTypeId' => 1]);
        Volunteer::factory()->create(['volunteerTypeId' => 2]);
        Volunteer::factory()->create(['volunteerTypeId' => 3]);
        Volunteer::factory()->create(['volunteerTypeId' => 4]);

        $response = $this->
        withHeaders([
            'Scorekeep-API-Key' => env('API_PUBLIC_KEY'),
        ])->
        putJson(route('games.addVolunteers', ['gameId' => $game->id]), [
            'timekeeperId' => 1,
            'secretaryId' => 1,
            'roomManagerId' => 1,
            'drinkManagerId' => 1,
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
     * Test add volunteers method with non-existing game.
     *
     * @return void
     */
    public function testAddVolunteersNonExistingGame()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create(
            [
                "email" => "email@mail.com",
                "password" => "password"
            ]
        );

        $response = $this->
        withHeaders([
            'Scorekeep-API-Key' => env('API_PUBLIC_KEY'),
        ])->postJson(route('api.login'), [
            'email' => "email@mail.com",
            'password' => "password",
        ]);

        VolunteerType::factory()->create();
        VolunteerType::factory()->create();
        VolunteerType::factory()->create();
        Volunteer::factory()->create(['volunteerTypeId' => 1]);
        Volunteer::factory()->create(['volunteerTypeId' => 2]);
        Volunteer::factory()->create(['volunteerTypeId' => 3]);

        $response = $this->
        withHeaders([
            'Scorekeep-API-Key' => env('API_PUBLIC_KEY'),
        ])->
        putJson(route('games.addVolunteers', ['gameId' => 9999]), [
            'timekeeperId' => 1,
            'secretaryId' => 1,
            'roomManagerId' => 1,
        ]);

        $response->assertNotFound();
        $response->assertJson([
            'message' => 'Match non trouvé',
        ]);
    }

    /**
     * Test add volunteers method with non-existing volunteer.
     *
     * @return void
     */
    public function testAddVolunteersNonExistingVolunteer()
    {
        $this->withoutExceptionHandling();
        VisitorTeam::factory()->create();
        $game = Game::factory()->create(["visitorTeamId" => 1]);

        $user = User::factory()->create(
            [
                "email" => "email@mail.com",
                "password" => "password"
            ]
        );

        $response = $this->
        withHeaders([
            'Scorekeep-API-Key' => env('API_PUBLIC_KEY'),
        ])->postJson(route('api.login'), [
            'email' => "email@mail.com",
            'password' => "password",
        ]);

        $response = $this->
        withHeaders([
            'Scorekeep-API-Key' => env('API_PUBLIC_KEY'),
        ])->
        putJson(route('games.addVolunteers', ['gameId' => 1]), [
            'timekeeperId' => 1,
            'secretaryId' => 1,
            'roomManagerId' => 1,
        ]);

        $response->assertNotFound();
        $response->assertJson([
            'message' => 'Paramètre de requête fournis incorrects ou inexistants',
        ]);
    }

    /**
     * Test the delete function.
     */
    public function testDeleteGame()
    {
        $user = User::factory()->create(
            [
                "email" => "email@mail.com",
                "password" => "password"
            ]
        );

        $response = $this->
        withHeaders([
            'Scorekeep-API-Key' => env('API_PUBLIC_KEY'),
        ])->postJson(route('api.login'), [
            'email' => "email@mail.com",
            'password' => "password",
        ]);

        $game = Game::factory()->create();

        $response = $this->withHeaders([
            'Scorekeep-API-Key' => env('API_PUBLIC_KEY'),
        ])->postJson(route('games.delete'), [
            'gameId' => $game->id,
        ]);

        $response->assertStatus(200);

        $this->assertSoftDeleted('games', ['id' => $game->id]);
    }

    /**
     * Test the confirm function.
     */
    public function testConfirmGame()
    {
        $user = User::factory()->create(
            [
                "email" => "email@mail.com",
                "password" => "password"
            ]
        );

        $response = $this->
        withHeaders([
            'Scorekeep-API-Key' => env('API_PUBLIC_KEY'),
        ])->postJson(route('api.login'), [
            'email' => "email@mail.com",
            'password' => "password",
        ]);

        $game = Game::factory()->create(['isCancelled' => true]);

        $response = $this->withHeaders([
            'Scorekeep-API-Key' => env('API_PUBLIC_KEY'),
        ])->postJson(route('games.confirm'), [
            'gameId' => $game->id,
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('games', [
            'id' => $game->id,
            'isCancelled' => false,
            'cancelledDate' => null,
        ]);
    }

    /**
     * Test the cancel function.
     */
    public function testCancelGame()
    {
        $user = User::factory()->create(
            [
                "email" => "email@mail.com",
                "password" => "password"
            ]
        );

        $response = $this->
        withHeaders([
            'Scorekeep-API-Key' => env('API_PUBLIC_KEY'),
        ])->postJson(route('api.login'), [
            'email' => "email@mail.com",
            'password' => "password",
        ]);

        $game = Game::factory()->create(['isCancelled' => false]);

        $response = $this->withHeaders([
            'Scorekeep-API-Key' => env('API_PUBLIC_KEY'),
        ])->postJson(route('games.cancel'), [
            'gameId' => $game->id,
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('games', [
            'id' => $game->id,
            'isCancelled' => true,
            'cancelledDate' => now(),
        ]);
    }
}
