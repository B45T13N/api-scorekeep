<?php

use App\Models\VolunteerType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class VolunteerTypeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testShow()
    {
        $volunteerType = VolunteerType::factory()->create();

        $response = $this->
        withHeaders([
            'Scorekeep-API-Key' => env('API_PUBLIC_KEY'),
        ])->
        getJson('/api/volunteer-types/show/'.$volunteerType->id);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'label' => $volunteerType->label
                ]
            ]);
    }

    public function testShowAll()
    {
        $volunteerType1 = VolunteerType::factory()->create();
        $volunteerType2 = VolunteerType::factory()->create();

        $response = $this->
        withHeaders([
            'Scorekeep-API-Key' => env('API_PUBLIC_KEY'),
        ])->
        getJson('/api/volunteer-types/show-all');

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    [
                        'id' => $volunteerType1->id,
                        'label' => $volunteerType1->label,
                    ],
                    [
                        'id' => $volunteerType2->id,
                        'label' => $volunteerType2->label,
                    ]
                ]
            ]);
    }

    public function testShowNonExistingVolunteerType()
    {
        $nonExistingVolunteerTypeId = 999;

        $response = $this->
        withHeaders([
            'Scorekeep-API-Key' => env('API_PUBLIC_KEY'),
        ])->
        getJson('/api/volunteer-types/show/'.$nonExistingVolunteerTypeId);

        $response->assertStatus(404)
            ->assertJson(['message' => 'Catégorie de bénévole non trouvée']);
    }
}
