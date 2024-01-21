<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\CustomResetPasswordNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ResetPasswordControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_request_password_reset_link()
    {
        Notification::fake();

        $user = User::factory()->create();

        $this->withHeaders([
            'Scorekeep-API-Key' => env('API_PUBLIC_KEY'),
        ])->post('/api/password/reset-link', ['email' => $user->email])
            ->assertStatus(200);

        Notification::assertSentTo($user, CustomResetPasswordNotification::class);
    }

    /** @test */
    public function user_can_reset_password_with_valid_token()
    {
        Notification::fake();

        $user = User::factory()->create();

        // Envoi du lien de réinitialisation
        $this->withHeaders([
            'Scorekeep-API-Key' => env('API_PUBLIC_KEY'),
        ])->post('/api/password/reset-link', ['email' => $user->email]);

        $token = '';
        Notification::assertSentTo($user, CustomResetPasswordNotification::class, function ($notification) use (&$token){
            $queryString = parse_url($notification->url, PHP_URL_QUERY);

            parse_str($queryString, $params);

            $token = $params['token'] ?? null;
            return true;
        });

        $response = $this->post('/api/password/reset', [
            'token' => $token,
            'email' => $user->email,
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
        ]);

        $response->assertStatus(200);

        $this->assertTrue(Hash::check('newpassword', $user->fresh()->password));
    }
}
