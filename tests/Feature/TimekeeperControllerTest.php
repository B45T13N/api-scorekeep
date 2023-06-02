<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TimekeeperControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testStore()
    {
        $name = 'John Doe';
        $email = 'johndoe@example.com';

        $response = $this->postJson('/api/timekeepers/store', [
            'name' => $name,
            'email' => $email,
        ]);

        $response->assertStatus(201)
            ->assertJson(['message' => 'Chronométreur enregistré avec succès']);

        $this->assertDatabaseHas('timekeepers', [
            'name' => $name,
            'email' => $email,
        ]);
    }


    public function testStoreWithInvalidData()
    {
        $response = $this->postJson('/api/timekeepers/store', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email']);
    }
}
