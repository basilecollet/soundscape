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
    $response->assertSee(__('admin.settings.section_visibility'));
    $response->assertSee(__('admin.settings.sections.home.title'));
    $response->assertSee(__('admin.settings.sections.about.title'));
});

test('admin section settings page shows section information', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/admin/section-settings');

    $response->assertStatus(200);
    // Should show available sections
    $response->assertSee(__('admin.settings.sections.home.features.title'));
    $response->assertSee(__('admin.settings.sections.home.cta.title'));
    $response->assertSee(__('admin.settings.sections.about.experience.title'));
    $response->assertSee(__('admin.settings.sections.about.services.title'));
    $response->assertSee(__('admin.settings.sections.about.philosophy.title'));
});

test('livewire component can toggle section settings', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test('admin.section-settings-manager')
        ->call('toggleSection', 'features', 'home')
        ->assertHasNoErrors()
        ->assertDispatched('section-toggled');
});
