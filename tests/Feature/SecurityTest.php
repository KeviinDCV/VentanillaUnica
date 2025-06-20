<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SecurityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Crear usuario de prueba
        User::create([
            'name' => 'Usuario Prueba',
            'email' => 'test@uniradical.com',
            'password' => Hash::make('password123'),
            'role' => 'ventanilla',
            'active' => true,
            'email_verified_at' => now(),
        ]);
    }

    /** @test */
    public function login_page_has_security_headers()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertHeader('X-Frame-Options', 'DENY');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-XSS-Protection', '1; mode=block');
    }

    /** @test */
    public function login_form_has_csrf_protection()
    {
        $response = $this->get('/login');
        
        $response->assertStatus(200);
        $response->assertSee('csrf-token', false);
        $response->assertSee('_token', false);
    }

    /** @test */
    public function successful_login_with_remember_me()
    {
        $response = $this->post('/login', [
            'email' => 'test@uniradical.com',
            'password' => 'password123',
            'remember' => true,
            '_token' => csrf_token()
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
        
        // Verificar que se estableció la cookie de remember
        $response->assertCookie('remember_web');
    }

    /** @test */
    public function failed_login_increments_rate_limit()
    {
        // Limpiar cache antes de la prueba
        Cache::flush();

        $response = $this->post('/login', [
            'email' => 'test@uniradical.com',
            'password' => 'wrong-password',
            '_token' => csrf_token()
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
        
        // Verificar que se incrementó el contador de intentos
        $this->assertTrue(Cache::has('login_attempts_127.0.0.1'));
    }

    /** @test */
    public function rate_limiting_blocks_after_max_attempts()
    {
        // Simular 5 intentos fallidos
        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', [
                'email' => 'test@uniradical.com',
                'password' => 'wrong-password',
                '_token' => csrf_token()
            ]);
        }

        // El sexto intento debe ser bloqueado
        $response = $this->post('/login', [
            'email' => 'test@uniradical.com',
            'password' => 'wrong-password',
            '_token' => csrf_token()
        ]);

        $response->assertStatus(429);
    }

    /** @test */
    public function inactive_user_cannot_login()
    {
        // Crear usuario inactivo
        $inactiveUser = User::create([
            'name' => 'Usuario Inactivo',
            'email' => 'inactive@uniradical.com',
            'password' => Hash::make('password123'),
            'role' => 'ventanilla',
            'active' => false,
            'email_verified_at' => now(),
        ]);

        $response = $this->post('/login', [
            'email' => 'inactive@uniradical.com',
            'password' => 'password123',
            '_token' => csrf_token()
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    /** @test */
    public function email_validation_is_strict()
    {
        $response = $this->post('/login', [
            'email' => 'invalid-email',
            'password' => 'password123',
            '_token' => csrf_token()
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function password_validation_requires_minimum_length()
    {
        $response = $this->post('/login', [
            'email' => 'test@uniradical.com',
            'password' => '123',
            '_token' => csrf_token()
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    /** @test */
    public function logout_invalidates_session()
    {
        // Login primero
        $this->post('/login', [
            'email' => 'test@uniradical.com',
            'password' => 'password123',
            '_token' => csrf_token()
        ]);

        $this->assertAuthenticated();

        // Logout
        $response = $this->post('/logout', [
            '_token' => csrf_token()
        ]);

        $response->assertRedirect('/');
        $this->assertGuest();
    }

    /** @test */
    public function session_regenerates_on_login()
    {
        $response = $this->get('/login');
        $initialSessionId = session()->getId();

        $this->post('/login', [
            'email' => 'test@uniradical.com',
            'password' => 'password123',
            '_token' => csrf_token()
        ]);

        $newSessionId = session()->getId();
        $this->assertNotEquals($initialSessionId, $newSessionId);
    }
}
