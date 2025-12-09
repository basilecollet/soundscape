<?php

use App\Models\SectionSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Seed default section settings for all tests
    $this->artisan('db:seed', ['--class' => 'DefaultSectionSettingsSeeder']);

    // Seed default content for all tests
    $this->artisan('db:seed', ['--class' => 'DefaultContentSeeder']);
});

test('home page shows all sections when enabled', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
    // These strings would be present when features section is enabled
    $response->assertSee('Mixing'); // Feature 1 title
    $response->assertSee(__('portfolio.home.cta.ready_title')); // CTA section
});

test('home page hides features section when disabled', function () {
    // Disable features section
    SectionSetting::updateOrCreate(
        ['section_key' => 'features', 'page' => 'home'],
        ['is_enabled' => false]
    );

    $response = $this->get('/');

    $response->assertStatus(200);
    // Features should not be visible
    $response->assertDontSee('Mixing'); // Feature 1 title should not appear
    $response->assertDontSee('Professional mixing services'); // Feature description
});

test('home page hides CTA section when disabled', function () {
    // Disable CTA section
    SectionSetting::updateOrCreate(
        ['section_key' => 'cta', 'page' => 'home'],
        ['is_enabled' => false]
    );

    $response = $this->get('/');

    $response->assertStatus(200);
    // CTA should not be visible
    $response->assertDontSee(__('portfolio.home.cta.ready_title'));
    $response->assertDontSee(__('portfolio.home.cta.ready_description'));
});

test('about page shows all sections when enabled', function () {
    $response = $this->get('/about');

    $response->assertStatus(200);
    // These would be present when sections are enabled
    $response->assertSee('10+'); // Experience years
    $response->assertSee(__('portfolio.about.services_title')); // Services section heading
    $response->assertSee(__('portfolio.about.philosophy_title')); // Philosophy section heading
});

test('about page hides experience section when disabled', function () {
    // Disable experience section
    SectionSetting::updateOrCreate(
        ['section_key' => 'experience', 'page' => 'about'],
        ['is_enabled' => false]
    );

    $response = $this->get('/about');

    $response->assertStatus(200);
    // Experience stats should not be visible
    $response->assertDontSee(__('portfolio.about.experience.years'));
    $response->assertDontSee(__('portfolio.about.experience.projects'));
    $response->assertDontSee(__('portfolio.about.experience.clients'));
});

test('about page hides services section when disabled', function () {
    // Disable services section
    SectionSetting::updateOrCreate(
        ['section_key' => 'services', 'page' => 'about'],
        ['is_enabled' => false]
    );

    $response = $this->get('/about');

    $response->assertStatus(200);
    // Services section should not be visible
    $response->assertDontSee(__('portfolio.about.services_title'));
    $response->assertDontSee('Recording'); // Service item
});

test('about page hides philosophy section when disabled', function () {
    // Disable philosophy section
    SectionSetting::updateOrCreate(
        ['section_key' => 'philosophy', 'page' => 'about'],
        ['is_enabled' => false]
    );

    $response = $this->get('/about');

    $response->assertStatus(200);
    // Philosophy section should not be visible
    $response->assertDontSee(__('portfolio.about.philosophy_title'));
    $response->assertDontSee('Every audio project tells a story');
});

test('admin can access section settings page', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/admin/section-settings');

    $response->assertStatus(200);
    $response->assertSee(__('admin.settings.section_visibility'));
    $response->assertSee(__('admin.settings.sections.home.title'));
    $response->assertSee(__('admin.settings.sections.about.title'));
});

test('section settings manager shows correct sections', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/admin/section-settings');

    $response->assertStatus(200);
    // Should show disableable sections
    $response->assertSee(__('admin.settings.sections.home.features.title'));
    $response->assertSee(__('admin.settings.sections.home.cta.title'));
    $response->assertSee(__('admin.settings.sections.about.experience.title'));
    $response->assertSee(__('admin.settings.sections.about.services.title'));
    $response->assertSee(__('admin.settings.sections.about.philosophy.title'));
});
