<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_login_through_api_and_receive_sanctum_token(): void
    {
        $user = User::factory()->create([
            'role' => 'customer',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $response
            ->assertOk()
            ->assertJson([
                'message' => 'Login successful.',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => 'customer',
                ],
            ])
            ->assertJsonStructure([
                'message',
                'token',
                'user' => [
                    'id',
                    'name',
                    'email',
                    'role',
                ],
            ]);

        $this->assertDatabaseHas('personal_access_tokens', [
            'tokenable_id' => $user->id,
        ]);
    }

    public function test_api_login_rejects_invalid_credentials(): void
    {
        $user = User::factory()->create([
            'role' => 'customer',
            'password' => bcrypt('correct-password'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response
            ->assertStatus(401)
            ->assertJson([
                'message' => 'Invalid email or password.',
            ]);
    }

    public function test_api_login_validates_required_fields(): void
    {
        $response = $this->postJson('/api/login', []);

        $response
            ->assertStatus(422)
            ->assertJson([
                'message' => 'Validation failed.',
            ])
            ->assertJsonValidationErrors([
                'email',
                'password',
            ]);
    }
    public function test_authenticated_user_can_logout_from_api_and_revoke_current_token(): void
    {
        $user = User::factory()->create([
            'role' => 'customer',
        ]);

        $plainTextToken = $user->createToken('api-token', [
            'read',
            'create',
            'update',
            'delete',
            'bookings:read',
            'bookings:create',
            'bookings:cancel',
        ])->plainTextToken;

        $this->assertDatabaseCount('personal_access_tokens', 1);

        $response = $this
            ->withHeader('Authorization', 'Bearer ' . $plainTextToken)
            ->postJson('/api/logout');

        $response
            ->assertOk()
            ->assertJson([
                'message' => 'Logout successful.',
            ]);

        $this->assertDatabaseCount('personal_access_tokens', 0);
    }
}

