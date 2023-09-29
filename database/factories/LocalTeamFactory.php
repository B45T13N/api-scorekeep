<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LocalTeam>
 */
class LocalTeamFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => "Avon Handball",
            'logo' => "https://avonhandball.fr/wp-content/uploads/2020/08/avon-handball-1.png",
            'token' => 77210
        ];
    }
}
