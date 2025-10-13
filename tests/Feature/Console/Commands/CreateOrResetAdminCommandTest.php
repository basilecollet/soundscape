<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Prompts\Prompt;

uses()->group('commands');
uses(RefreshDatabase::class);

test('creates new admin account with provided credentials', function () {
    // Arrange: Ensure no user exists with this email
    expect(User::where('email', 'admin@example.com')->exists())->toBeFalse();

    // Fake prompts (won't be called because we provide options)
    Prompt::fake();

    // Act: Run the command with options
    $this->artisan('admin:create-or-reset', [
        '--email' => 'admin@example.com',
        '--name' => 'Admin User',
        '--password' => 'SecurePassword123!',
    ])
        ->expectsOutput('✅ Admin account created successfully!')
        ->assertSuccessful();

    // Assert: User was created with correct data
    $user = User::where('email', 'admin@example.com')->first();
    assert($user !== null);

    expect($user->name)->toBe('Admin User')
        ->and($user->email)->toBe('admin@example.com')
        ->and(Hash::check('SecurePassword123!', $user->password))->toBeTrue()
        ->and($user->email_verified_at)->not->toBeNull();
});

test('creates new admin account with generated password when no password provided', function () {
    // Arrange: Ensure no user exists with this email
    expect(User::where('email', 'newadmin@example.com')->exists())->toBeFalse();

    // Fake prompts (won't be called because we provide options)
    Prompt::fake();

    // Act: Run the command without password option (triggers generation)
    $this->artisan('admin:create-or-reset', [
        '--email' => 'newadmin@example.com',
        '--name' => 'New Admin',
    ])
        ->expectsOutput('Generated secure password for admin account.')
        ->expectsOutput('✅ Admin account created successfully!')
        ->assertSuccessful();

    // Assert: User was created with a password
    $user = User::where('email', 'newadmin@example.com')->first();
    assert($user !== null);

    expect($user->name)->toBe('New Admin')
        ->and($user->email)->toBe('newadmin@example.com')
        ->and($user->password)->not->toBeEmpty()
        ->and($user->email_verified_at)->not->toBeNull();
});

test('resets existing admin password', function () {
    // Arrange: Create an existing user
    $existingUser = User::factory()->create([
        'email' => 'existing@example.com',
        'name' => 'Existing Admin',
        'password' => Hash::make('OldPassword123'),
    ]);

    $oldPassword = $existingUser->password;

    // Fake prompts (won't be called because we provide options)
    Prompt::fake();

    // Act: Run the command to reset password
    $this->artisan('admin:create-or-reset', [
        '--email' => 'existing@example.com',
        '--name' => 'Updated Admin',
        '--password' => 'NewPassword456!',
    ])
        ->expectsOutput('✅ Admin password updated successfully!')
        ->assertSuccessful();

    // Assert: User was updated
    $user = User::where('email', 'existing@example.com')->first();
    assert($user !== null);

    expect($user->name)->toBe('Updated Admin')
        ->and($user->password)->not->toBe($oldPassword)
        ->and(Hash::check('NewPassword456!', $user->password))->toBeTrue();
});
