<?php


use App\Models\Game;
use App\Models\LocalTeam;
use App\Models\VisitorTeam;
use App\Models\Volunteer;
use App\Models\VolunteerType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VolunteerControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testStore()
    {
        VisitorTeam::factory()->create();
        VolunteerType::factory()->create();
        LocalTeam::factory()->create(['token'=>1234]);
        Game::factory()->create(["visitorTeamId" => 1]);

        $name = 'John Doe';
        $token = 1234;
        $gameId = 1;
        $volunteerTypeId = 1;

        $response = $this->
        withHeaders([
            'Scorekeep-API-Key' => env('API_PUBLIC_KEY'),
        ])->
        postJson('/api/volunteers/store', [
            'name' => $name,
            'token' => $token,
            'gameId' => $gameId,
            'volunteerTypeId' => $volunteerTypeId
        ]);

        $response->assertStatus(201)
        ->assertJson(['message' => 'Bénévole enregistré avec succès']);

        $this->assertDatabaseHas('volunteers', [
            'name' => $name,
            'gameId' => $gameId,
            'volunteerTypeId' => $volunteerTypeId
        ]);
    }


    public function testStoreWithInvalidData()
    {
        $response = $this->
        withHeaders([
            'Scorekeep-API-Key' => env('API_PUBLIC_KEY'),
        ])->
        postJson('/api/volunteers/store', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'token']);
    }

    public function testStoreWithException()
    {
        $name = 'John Doe';
        $token = 1234;
        $gameId = 1;
        $volunteerTypeId = 1;

        $volunteerMock = Mockery::mock(Volunteer::class)->makePartial();
        $volunteerMock->shouldReceive('save')->andReturnUsing(function () {
            throw new \Exception('Erreur lors de l\'enregistrement du bénévole');
        });
        $this->app->instance(Volunteer::class, $volunteerMock);

        $response = $this->
        withHeaders([
            'Scorekeep-API-Key' => env('API_PUBLIC_KEY'),
        ])->
        postJson('/api/volunteers/store', [
            'name' => $name,
            'token' => $token,
            'gameId' => $gameId,
            'volunteerTypeId' => $volunteerTypeId
        ]);

        $response->assertStatus(404)
            ->assertJson(['message' => 'Erreur lors de l\'enregistrement du bénévole']);
    }

}
