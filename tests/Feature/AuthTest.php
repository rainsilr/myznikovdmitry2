<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_is_available_for_guest(): void
    {
        $this->get('/login')->assertStatus(200);
    }

    public function test_user_can_register(): void
    {
        $response = $this->post('/register', [
            'fio' => 'Иванов Иван Иванович',
            'login' => 'ivan123',
            'email' => 'ivan@example.ru',
            'password' => 'secret1',
            'phone' => '+7(999)123-45-67',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'login' => 'ivan123',
            'role' => 'user',
        ]);
    }

    public function test_invalid_login_shows_error(): void
    {
        User::factory()->create([
            'login' => 'petrov',
            'password' => 'correct-password',
        ]);

        $response = $this->from('/login')->post('/login', [
            'login' => 'petrov',
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors(['login' => 'Неверный логин или пароль']);
    }
}
