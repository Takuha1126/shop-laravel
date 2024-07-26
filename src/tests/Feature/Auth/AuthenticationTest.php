<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_login_screen()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);

        $userData = [
        'email' => 'test@example.com',
        'password' => 'password',
        '_token' => csrf_token(),
        ];

        $this->flushSession();

        $response = $this->post(route('login'), $userData);

        $response->assertRedirect(route('home.index'));
        $this->assertAuthenticated();
    }

    public function test_users_can_not_authenticate_with_invalid_password()
    {
        $userData = [
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ];

        User::create($userData);

        $response = $this->post('/login', [
            'email' => $userData['email'],
            'password' => 'wrong-password',
            '_token' => csrf_token(),
        ]);

        $this->assertGuest();
    }
}
