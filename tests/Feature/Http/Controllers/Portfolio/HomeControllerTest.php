<?php

use App\Models\PageContent;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('home page can be accessed', function () {
    // Given: Minimum content exists
    PageContent::factory()->create(['key' => 'home_hero_title', 'content' => 'Soundscape Audio', 'page' => 'home']);
    PageContent::factory()->create(['key' => 'home_hero_subtitle', 'content' => 'Professional Audio Engineering', 'page' => 'home']);
    PageContent::factory()->create(['key' => 'home_hero_text', 'content' => 'Transform your audio projects with industry-standard expertise', 'page' => 'home']);

    // And: Sections are disabled
    \App\Models\SectionSetting::factory()->create(['section_key' => 'features', 'page' => 'home', 'is_enabled' => false]);
    \App\Models\SectionSetting::factory()->create(['section_key' => 'cta', 'page' => 'home', 'is_enabled' => false]);

    $response = $this->get('/');

    $response->assertStatus(200)
        ->assertViewIs('portfolio.home')
        ->assertSee('Soundscape Audio');
});

test('home page has proper SEO meta tags', function () {
    // Given: Minimum content exists
    PageContent::factory()->create(['key' => 'home_hero_title', 'content' => 'Soundscape Audio', 'page' => 'home']);
    PageContent::factory()->create(['key' => 'home_hero_subtitle', 'content' => 'Professional Audio Engineering', 'page' => 'home']);
    PageContent::factory()->create(['key' => 'home_hero_text', 'content' => 'Transform your audio projects with industry-standard expertise', 'page' => 'home']);

    // And: Sections are disabled
    \App\Models\SectionSetting::factory()->create(['section_key' => 'features', 'page' => 'home', 'is_enabled' => false]);
    \App\Models\SectionSetting::factory()->create(['section_key' => 'cta', 'page' => 'home', 'is_enabled' => false]);

    $response = $this->get('/');

    $response->assertStatus(200)
        ->assertSee('<meta name="description"', false)
        ->assertSee('<meta property="og:title"', false)
        ->assertSee('<meta property="og:description"', false);
});

test('home page displays content from ContentService', function () {
    // Given: Minimum content exists
    PageContent::factory()->create(['key' => 'home_hero_title', 'content' => 'Soundscape Audio', 'page' => 'home']);
    PageContent::factory()->create(['key' => 'home_hero_subtitle', 'content' => 'Professional Audio Engineering', 'page' => 'home']);
    PageContent::factory()->create(['key' => 'home_hero_text', 'content' => 'Transform your audio projects with industry-standard expertise', 'page' => 'home']);

    // And: Sections are disabled
    \App\Models\SectionSetting::factory()->create(['section_key' => 'features', 'page' => 'home', 'is_enabled' => false]);
    \App\Models\SectionSetting::factory()->create(['section_key' => 'cta', 'page' => 'home', 'is_enabled' => false]);

    $response = $this->get('/');

    $response->assertStatus(200)
        ->assertSee('Professional Audio Engineering')
        ->assertSee('Transform your audio projects with industry-standard expertise');
});
