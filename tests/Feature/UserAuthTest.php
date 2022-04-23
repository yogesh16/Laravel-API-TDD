<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserAuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A user login required email and password.
     */
    public function test_user_login_required_email_password()
    {
        $this->json('POST', '/api/auth/login', [], ['Accept' => 'application/json'])
             ->assertStatus(422)
             ->assertJson([
                "message" => "The given data was invalid.",
                "errors" => [
                    'email' => ["The email field is required."],
                    'password' => ["The password field is required."],
                ]
             ]);
    }

    /**
     * A User can login
     */
    public function test_user_can_login()
    {
        $user = User::factory()->create();
        $userData = [
            'email' => $user->email,
            'password' => 'password'
        ];

        $this->json('POST', '/api/auth/login', $userData, ['Accept' => 'application/json'])
             ->assertStatus(200)
             ->assertJsonStructure([
                "user" => [
                    "id",
                    "name",
                    "email",
                    "email_verified_at",
                    "created_at",
                    "updated_at"
                ],
                "access_token",
                "message"
             ]);

        $this->assertAuthenticated();
    }

    /**
     * User should not allowed to login with false credentials
     */
    public function test_user_can_not_login_with_false_credentials()
    {
        $user = User::factory()->create();
        $userData = [
            'email' => $user->email,
            'password' => 'test123!'
        ];
        $this->json('POST', '/api/auth/login', $userData, ['Accept' => 'application/json'])
             ->assertStatus(200)
             ->assertJson([
                "message" => 'Invalid Username or Password'
             ]);
    }
}
