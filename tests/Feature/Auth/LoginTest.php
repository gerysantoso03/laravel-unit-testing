<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;

beforeEach(function() {
    // Register new user data
    $registeredUser = User::factory()->create([
        'name' => 'Gery Santoso',
        'email' => 'gerysantos03@gmail.com',
        'password' => '0Plet09!#'
    ]);

    $this->registeredUser = $registeredUser;
});

describe('auth', function() {
    beforeEach(fn() => $this->actingAs($this->registeredUser));

    test('user cannot login when already login', function () {
        // Try to login while already authenticated
        $credentials = [
            'email' => 'gerysantos03@gmail.com',
            'password' => '0Plet09!#'
        ];

        $response = $this->post(route('login.post'), $credentials);

        // Should redirect to dashboard as they're already logged in
        $response->assertRedirect(route('dashboard'));
        
        // Verify user is still the same
        expect(Auth::user())
            ->email->toBe($this->registeredUser['email'])
            ->name->toBe($this->registeredUser['name']);
    });

    test('user can logout', function () {
        // Verify user is authenticated before logout
        expect(Auth::check())->toBeTrue();

        // Perform logout
        $response = $this->get(route('logout'));

        // Assert redirect to login page
        $response->assertRedirect(route('login'));

        // Verify user is no longer authenticated
        expect(Auth::check())->toBeFalse();
        expect(Auth::user())->toBeNull();
    });
});

describe('guest', function() {
    test('user can view login page', function () {
        $response = $this->get(route('login'));
    
        // Response result assertion
        $response
            ->assertStatus(200)
            ->assertViewIs('auth.login');
    });
    
    test('all fields must be filled inside login form', function () {
        // Login credentials
        $credentials = [
            'email' => '',
            'password' => ''
        ];
    
        $response = $this->post(route('login.post'), $credentials);
    
        $response->assertSessionHasErrors(['email', 'password']);
    
        // Expect authenticated user to be false
        expect(Auth::check())->toBeFalse();
    
        $authenticatedUser = Auth::user();
        expect($authenticatedUser)->toBeNull();
    });
    
    test('user cannot login with invalid credentials', function () {
        // Login credentials
        $credentials = [
            'email' => 'gerysantos04@gmail.com',
            'password' => '0Plet09!#'
        ];
    
        $response = $this->post(route('login.post'), $credentials);
    
        // Response result assertion
        $response->assertRedirect(route('login'))->assertSessionHas('error', 'Invalid email or password');
    
        // Expect authenticated user to be false
        expect(Auth::check())->toBeFalse();
    
        $authenticatedUser = Auth::user();
        expect($authenticatedUser)->toBeNull();
    });
    
    
    // Login using followingRedirects -> make test performs any redirects the response sends — just like a real browser would.
    /**
     * Laravel sends the POST /login request.
     * The response says “redirect to /”.
     * The test client automatically requests /.
     * The final $response now contains the actual rendered page (e.g. welcome view).
     */
    test('user can login using registered account by followingRedirects method', function () {
        // Login credentials
        $credentials = [
            'email' => 'gerysantos03@gmail.com',
            'password' => '0Plet09!#'
        ];
    
        $response = $this->followingRedirects()->post(route('login.post'), $credentials);
    
        // Response result assertion
        $response->assertViewIs('welcome');
    
        // Expect authenticated user data
        expect(Auth::check())->toBeTrue();
    
        $authenticatedUser = Auth::user();
        expect($authenticatedUser)
            ->email->toBe($this->registeredUser['email'])
            ->name->toBe($this->registeredUser['name']);
    });
    
    test('user can login using registered account', function () {
        // Login credentials
        $credentials = [
            'email' => 'gerysantos03@gmail.com',
            'password' => '0Plet09!#'
        ];
    
        $response = $this->post(route('login.post'), $credentials);
    
        // Response result assertion
        $response->assertRedirect(route('dashboard'));
    
        // Expect authenticated user data
        expect(Auth::check())->toBeTrue();
    
        $authenticatedUser = Auth::user();
        expect($authenticatedUser)
            ->email->toBe($this->registeredUser['email'])
            ->name->toBe($this->registeredUser['name']);
    });
});

