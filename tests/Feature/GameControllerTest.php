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
        $uuid = fake()->uuid;
        Game::factory(['visitorTeamId' => $uuid, 'gameDate' => Carbon::tomorrow("Europe/Paris")->toDateTimeString()]);
        Game::factory(['visitorTeamId' =>fake()->uuid, 'gameDate' => Carbon::tomorrow("Europe/Paris")->toDateTimeString()])->count(2)->create();
        $today = Carbon::now("Europe/Paris")->format("Y-m-d");

        $parameters = [
            'local_team_id' => $uuid,
            'start_date' => $today,
            'end_date' => Carbon::now()->addWeek()->format("Y-m-d"),
        ];

        $response = $this->
        withHeaders([
            'Scorekeep-API-Key' => env('API_PUBLIC_KEY'),
        ])
            ->getJson(route('games.index', $parameters));

        $response->assertOk();
        $response->assertJsonCount(3);
    }

    /**
     * Test weekGames method.
     *
     * @return void
     */
    public function testWeekGames()
    {
        VisitorTeam::factory()->create();

        Game::factory(['visitorTeamId' => fake()->uuid, 'gameDate' => Carbon::tomorrow("Europe/Paris")->toDateTimeString()])->count(3)->create();
        $parameters = [
            'local_team_id' => 1,
        ];
        $response = $this->
        withHeaders([
            'Scorekeep-API-Key' => env('API_PUBLIC_KEY'),
        ])
            ->getJson(route('games.weekGames', $parameters));

        $response->assertOk();
        $response->assertJsonCount(3);
    }


    /**
     * Test store method.
     *
     * @return void
     */
    public function testStore()
    {
        $localTeam = LocalTeam::factory()->create();

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
            'localTeamId' => $localTeam->uuid,
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
        $game = Game::factory()->create(["visitorTeamId" => fake()->uuid]);

        $response = $this->
        withHeaders([
            'Scorekeep-API-Key' => env('API_PUBLIC_KEY'),
        ])->
        getJson(route('games.show', ['gameId' => $game->uuid]));

        $response->assertOk();
        $response->assertJson(
            [
                "uuid" => $game->uuid,
                "address" => $game->address,
                "category" => $game->category,
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

        $game = Game::factory()->create(["visitorTeamId" => fake()->uuid]);
        $date = Carbon::now()->addDay()->toDateTimeString();
        $address = "14 rue du temple 75000 Paris";
        $response = $this->
        withHeaders([
            'Scorekeep-API-Key' => env('API_PUBLIC_KEY'),
        ])->
        putJson(route('games.update', ['gameId' => $game->uuid]), [
            'gameDate' => $date,
            'address' => $address
        ]);

        $response->assertOk();
        $response->assertJson(['message' => 'Match mis à jour avec succès']);

        $this->assertDatabaseHas('games', [
            'uuid' => $game->uuid,
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
        putJson(route('games.update', ['gameId' => fake()->uuid]), [
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

        $game = Game::factory()->create(["visitorTeamId" => fake()->uuid]);

        $volunteerTypes = VolunteerType::factory(4)->create();
        $vol1 = Volunteer::factory()->create(['volunteerTypeId' => $volunteerTypes[0]->uuid]);
        $vol2 = Volunteer::factory()->create(['volunteerTypeId' => $volunteerTypes[1]->uuid]);
        $vol3 = Volunteer::factory()->create(['volunteerTypeId' => $volunteerTypes[2]->uuid]);
        $vol4 = Volunteer::factory()->create(['volunteerTypeId' => $volunteerTypes[3]->uuid]);

        $response = $this->
        withHeaders([
            'Scorekeep-API-Key' => env('API_PUBLIC_KEY'),
        ])->
        putJson(route('games.addVolunteers', ['gameId' => $game->uuid]), [
            'timekeeperId' => $vol1->uuid,
            'secretaryId' => $vol2->uuid,
            'roomManagerId' => $vol3->uuid,
            'drinkManagerId' => $vol4->uuid,
        ]);

        $response->assertOk();
        $response->assertJson(['message' => 'Match mis à jour avec succès']);

        $this->assertDatabaseHas('games', [
            'uuid' => $game->uuid,
            'timekeeperId' => $vol1->uuid,
            'secretaryId' => $vol2->uuid,
            'roomManagerId' => $vol3->uuid,
            'drinkManagerId' => $vol4->uuid,
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
        $visitorTeam = VisitorTeam::factory()->create();
        $game = Game::factory()->create(["visitorTeamId" => $visitorTeam->uuid]);

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
        putJson(route('games.addVolunteers', ['gameId' => $game->uuid]), [
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
            'gameId' => $game->uuid,
        ]);

        $response->assertStatus(200);

        $this->assertSoftDeleted('games', ['uuid' => $game->uuid]);
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
            'gameId' => $game->uuid,
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('games', [
            'uuid' => $game->uuid,
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
            'gameId' => $game->uuid,
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('games', [
            'uuid' => $game->uuid,
            'isCancelled' => true,
            'cancelledDate' => now(),
        ]);
    }
}
