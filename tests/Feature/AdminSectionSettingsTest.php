<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Seed default section settings for all tests
    $this->artisan('db:seed', ['--class' => 'DefaultSectionSettingsSeeder']);
});

test('admin section settings page loads correctly', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/admin/section-settings');

    $response->assertStatus(200);
    $response->assertSee('Section Settings');
    $response->assertSee('home');
    $response->assertSee('about');
});

test('admin section settings page shows section information', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/admin/section-settings');

    $response->assertStatus(200);
    // Should show available sections
    $response->assertSee('Features Section');
    $response->assertSee('Call to Action Section');
    $response->assertSee('Experience Stats Section');
    $response->assertSee('Services Section');
    $response->assertSee('Philosophy Section');
});

test('livewire component can toggle section settings', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test('admin.section-settings-manager')
        ->call('toggleSection', 'features', 'home')
        ->assertHasNoErrors()
        ->assertDispatched('section-toggled');
});
