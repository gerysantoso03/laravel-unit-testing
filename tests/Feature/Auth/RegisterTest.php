<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

test('user can view register page', function () {
    $response = $this->get(route('register'));

    // Request result assertion
    $response
        ->assertStatus(200)
        ->assertViewIs('auth.register');
});


test('user must filled all fields inside register form', function () {
    // Empty register input fields
    $emptyUserData = [
        'name' => '',
        'email' => '',
        'password' => '',
        'password_confirmation' => ''
    ];

    $response = $this->post(route('register.post'), $emptyUserData);

    $response->assertSessionHasErrors(['name', 'email', 'password']);
    $this->assertDatabaseCount('users', 0);
});

test('user must fill valid email format inside register form', function () {
    // Invalid email format
    $userData = [
        'name' => 'Gery Santoso',
        'email' => 'gerysantosgmai.com',
        'password' => '0Plet09!#',
        'password_confirmation' => '0Plet09!#'
    ];

    $response = $this->post(route('register.post'), $userData);

    $response->assertSessionHasErrors('email');
    $this->assertDatabaseCount('users', 0);
});

test('both password and confirm password must match', function () {
    // Invalid email format
    $userData = [
        'name' => 'Gery Santoso',
        'email' => 'gerysantos@gmail.com',
        'password' => '0Plet09!#',
        'password_confirmation' => '0Plet09'
    ];

    $response = $this->post(route('register.post'), $userData);

    $response->assertSessionHasErrors('password');
    $this->assertDatabaseCount('users', 0);
});

test('user cannot register new account with duplicate email', function () {
    // Create existing user
    User::factory()->create(['email' => 'gerysantos03@gmail.com']);

    // New user data
    $newUser = [
        'name' => 'Gery Santoso',
        'email' => 'gerysantos03@gmail.com',
        'password' => '0Plet09!#',
        'password_confirmation' => '0Plet09!#'
    ];

    $response = $this->post(route('register.post'), $newUser);

    $response->assertSessionHasErrors('email');
    $this->assertDatabaseCount('users', 1);
});

test('user can create new account', function () {
    // Create register request data
    $user = [
       'name' => 'Gery Santoso',
       'email' => 'gerysantos03@gmail.com',
       'password' => '0Plet09!#',
       'password_confirmation' => '0Plet09!#'
    ];

    $response = $this->post(route('register.post'), $user);

    $response->assertRedirect()->assertSessionHas('success', 'Success register new user.');

    // Check registered user in the database
    $registeredUser = User::latest('id')->first();

    expect($registeredUser)
        ->name->toBe($user['name'])
        ->email->toBe($user['email']);
});

