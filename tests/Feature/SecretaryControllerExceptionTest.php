<?php

namespace Tests\Feature;

use App\Models\Secretary;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Mockery;
use Tests\TestCase;

class SecretaryControllerExceptionTest extends TestCase
{
    use DatabaseTransactions;

    public function testStoreWithException()
    {
        $name = 'John Doe';
        $email = 'johndoe@example.com';

        // Forcer l'échec de l'enregistrement en utilisant une fausse méthode save
        $roomManagerMock = Mockery::mock(Secretary::class)->makePartial();
        $roomManagerMock->shouldReceive('save')->andReturnUsing(function () {
            throw new \Exception('Erreur lors de l\'enregistrement du secrétaire');
        });
        $this->app->instance(Secretary::class, $roomManagerMock);

        $response = $this->postJson('/api/secretaries/store', [
            'name' => $name,
            'email' => $email,
        ]);

        $response->assertStatus(404)
            ->assertJson(['message' => 'Erreur lors de l\'enregistrement du secrétaire']);
    }
}
