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

//        LocalTeam::factory(1)->create();
//        \App\Models\User::factory()->create([
//            'name' => 'Test User',
//            'email' => 'test@example.com',
//            'password' => 'password',
//        ]);
//        VisitorTeam::factory(15)->create();
//        Game::factory(10)->create();

//        Volunteer::factory(15)->create();

        VolunteerType::factory()->create(['label' => "Secrétaire"]);
        VolunteerType::factory()->create(['label' => "Chronométreur"]);
        VolunteerType::factory()->create(['label' => "Responsable de salle"]);
        VolunteerType::factory()->create(['label' => "Buvette"]);
    }
}
