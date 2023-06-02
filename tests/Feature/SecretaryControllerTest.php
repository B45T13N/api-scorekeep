<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SecretaryControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testStore()
    {
        $name = 'John Doe';
        $email = 'johndoe@example.com';

        $response = $this->postJson('/api/secretaries/store', [
            'name' => $name,
            'email' => $email,
        ]);

        $response->assertStatus(201)
            ->assertJson(['message' => 'Secrétaire enregistré avec succès']);

        $this->assertDatabaseHas('secretaries', [
            'name' => $name,
            'email' => $email,
        ]);
    }


    public function testStoreWithInvalidData()
    {
        $response = $this->postJson('/api/secretaries/store', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email']);
    }
}
