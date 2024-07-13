<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


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
        'email' => 'john@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ];

    $response = $this->post(route('register'), $userData);

    $response->assertStatus(302);
    $response->assertRedirect('/email/verify');
    $this->assertAuthenticated();
}

}
