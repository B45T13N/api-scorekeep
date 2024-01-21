<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Game;
use App\Models\LocalTeam;
use App\Models\VisitorTeam;
use App\Models\VolunteerType;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        $localTeam = LocalTeam::factory()->create([
            'name' => "Avon Handball",
            'logo' => "https://avonhandball.fr/wp-content/uploads/2020/08/avon-handball-1.png",
            'token' => 77210
        ]);
        \App\Models\User::factory()->create([
            'localTeamId' => $localTeam->uuid,
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
        ]);
        $visitorTeams = VisitorTeam::factory(5)->create();

        Game::factory(2)->create([
            'localTeamId' => $localTeam->uuid,
            'visitorTeamId' => $visitorTeams[0]->uuid,
        ]);

        Game::factory(2)->create([
            'localTeamId' => $localTeam->uuid,
            'visitorTeamId' => $visitorTeams[1]->uuid,
        ]);

        Game::factory(2)->create([
            'localTeamId' => $localTeam->uuid,
            'visitorTeamId' => $visitorTeams[2]->uuid,
        ]);

        Game::factory(2)->create([
            'localTeamId' => $localTeam->uuid,
            'visitorTeamId' => $visitorTeams[3]->uuid,
        ]);

        Game::factory(2)->create([
            'localTeamId' => $localTeam->uuid,
            'visitorTeamId' => $visitorTeams[4]->uuid,
        ]);

//        Volunteer::factory(15)->create();

        VolunteerType::factory()->create(['label' => "Secrétaire"]);
        VolunteerType::factory()->create(['label' => "Chronométreur"]);
        VolunteerType::factory()->create(['label' => "Responsable de salle"]);
        VolunteerType::factory()->create(['label' => "Buvette"]);
    }
}
