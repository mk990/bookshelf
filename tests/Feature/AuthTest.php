<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_register_user(): void
    {
        $user = User::factory()->create();
        $response = $this->post('api/auth/register', [
            'first_name'       => $user->first_name,
            'last_name'        => $user->last_name,
            'email'            => $user->email,
            'password'         => $user->password,
        ]);

        $this->assertDatabaseHas('users', [
            'first_name'     => $user->first_name,
            'last_name'      => $user->last_name,
            'email'          => $user->email,
            'password'       => $user->password,

        ]);
        $response->assertStatus(302);
    }

    public function test_login_user(): void
    {
        $user = User::factory()->create();
        $response = $this->post('api/auth/login', [
            'email'            => $user->email,
            'password'         => 'password'
        ]);
        $this->assertAuthenticatedAs($user);
        $response->assertStatus(200);
    }
}
