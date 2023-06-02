<?php

namespace Tests\Feature;

use App\Models\RoomManager;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class RoomManagerControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testStore()
    {
        $name = 'John Doe';
        $email = 'johndoe@example.com';

        $response = $this->postJson('/api/room-managers/store', [
            'name' => $name,
            'email' => $email,
        ]);

        $response->assertStatus(201)
        ->assertJson(['message' => 'Responsable de salle enregistré avec succès']);

        $this->assertDatabaseHas('room_managers', [
            'name' => $name,
            'email' => $email,
        ]);
    }


    public function testStoreWithInvalidData()
    {
        $response = $this->postJson('/api/room-managers/store', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email']);
    }

}
