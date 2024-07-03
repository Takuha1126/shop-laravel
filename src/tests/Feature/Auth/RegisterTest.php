<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_form_can_be_rendered()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
        $response->assertViewIs('auth.register');
    }

    public function test_user_can_register()
{
    $userData = [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ];

    $response = $this->post(route('register'), $userData);

    $response->assertStatus(302); // リダイレクトされることを確認
    $response->assertRedirect(route('home.index')); // リダイレクト先が正しいことを確認
    $this->assertAuthenticated(); // ユーザーが認証されていることを確認
}

}
