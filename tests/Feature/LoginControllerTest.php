<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testLoginWithValidCredentials()
    {
        $password = 'testpassword';
        $user = User::factory()->create([
            'password' => $password,
        ]);

        $response = $this->
        withHeaders([
            'Scorekeep-API-Key' => env('API_PUBLIC_KEY'),
        ])->postJson(route('api.login'), [
            'email' => $user->email,
            'password' => $password,
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['access_token', 'token_type']);
    }

    public function testLoginWithInvalidCredentials()
    {
        $response = $this->
        withHeaders([
            'Scorekeep-API-Key' => env('API_PUBLIC_KEY'),
        ])->postJson(route('api.login'), [
            'email' => 'invalid@example.com',
            'password' => 'invalidpassword',
        ]);

        $response->assertStatus(401)
            ->assertJson(['message' => 'Invalid login details']);
    }

    public function testMe()
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


        $response = $this->actingAs($user)
            ->post(route('api.me'));

        $response->assertStatus(200)
            ->assertJson(['status' => true, 'user' => [
                'id' => $user->id,
                'email' => $user->email,
            ]]);
    }
}
