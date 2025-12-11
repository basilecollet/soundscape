<?php

use App\Application\Portfolio\Services\ContentService;
use App\Models\PageContent;
use App\Models\SectionSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('about page is accessible', function () {
    // Given: Minimum content exists
    PageContent::factory()->create(['key' => 'about_title', 'content' => 'About Soundscape', 'page' => 'about']);
    PageContent::factory()->create(['key' => 'about_intro', 'content' => 'Professional audio engineer', 'page' => 'about']);
    PageContent::factory()->create(['key' => 'about_bio', 'content' => 'Specializing in mixing and mastering', 'page' => 'about']);

    // And: Sections are disabled
    SectionSetting::factory()->create(['section_key' => 'experience', 'page' => 'about', 'is_enabled' => false]);
    SectionSetting::factory()->create(['section_key' => 'services', 'page' => 'about', 'is_enabled' => false]);
    SectionSetting::factory()->create(['section_key' => 'philosophy', 'page' => 'about', 'is_enabled' => false]);

    $response = $this->get('/about');

    $response->assertStatus(200);
});

test('about page contains proper SEO meta tags', function () {
    // Given: Minimum content exists
    PageContent::factory()->create(['key' => 'about_title', 'content' => 'About Soundscape', 'page' => 'about']);
    PageContent::factory()->create(['key' => 'about_intro', 'content' => 'Professional audio engineer', 'page' => 'about']);
    PageContent::factory()->create(['key' => 'about_bio', 'content' => 'Specializing in mixing and mastering', 'page' => 'about']);

    // And: Sections are disabled
    SectionSetting::factory()->create(['section_key' => 'experience', 'page' => 'about', 'is_enabled' => false]);
    SectionSetting::factory()->create(['section_key' => 'services', 'page' => 'about', 'is_enabled' => false]);
    SectionSetting::factory()->create(['section_key' => 'philosophy', 'page' => 'about', 'is_enabled' => false]);

    $response = $this->get('/about');

    $response->assertSee('<title>About Soundscape - Professional Audio Engineering</title>', false);
    $response->assertSee('<meta name="description"', false);
    $response->assertSee('<meta name="keywords"', false);
});

test('about page displays content from ContentService', function () {
    // Given: Minimum content exists
    PageContent::factory()->create(['key' => 'about_title', 'content' => 'About Soundscape', 'page' => 'about']);
    PageContent::factory()->create(['key' => 'about_intro', 'content' => 'Professional audio engineer', 'page' => 'about']);
    PageContent::factory()->create(['key' => 'about_bio', 'content' => 'Specializing in mixing and mastering', 'page' => 'about']);

    // And: Sections are disabled
    SectionSetting::factory()->create(['section_key' => 'experience', 'page' => 'about', 'is_enabled' => false]);
    SectionSetting::factory()->create(['section_key' => 'services', 'page' => 'about', 'is_enabled' => false]);
    SectionSetting::factory()->create(['section_key' => 'philosophy', 'page' => 'about', 'is_enabled' => false]);

    $contentService = app(ContentService::class);
    $content = $contentService->getAboutContent();

    $response = $this->get('/about');

    $response->assertSee($content['title']);
    $response->assertSee($content['intro']);
    $response->assertSee($content['bio']);
});

test('about page shows all services when section is enabled', function () {
    // Given: Minimum content + services exist
    PageContent::factory()->create(['key' => 'about_title', 'content' => 'About Soundscape', 'page' => 'about']);
    PageContent::factory()->create(['key' => 'about_intro', 'content' => 'Professional audio engineer', 'page' => 'about']);
    PageContent::factory()->create(['key' => 'about_bio', 'content' => 'Specializing in mixing and mastering', 'page' => 'about']);

    // And: Services content exists
    PageContent::factory()->create(['key' => 'about_service_1', 'content' => 'Recording', 'page' => 'about']);
    PageContent::factory()->create(['key' => 'about_service_2', 'content' => 'Mixing', 'page' => 'about']);
    PageContent::factory()->create(['key' => 'about_service_3', 'content' => 'Mastering', 'page' => 'about']);
    PageContent::factory()->create(['key' => 'about_service_4', 'content' => 'Sound Design', 'page' => 'about']);
    PageContent::factory()->create(['key' => 'about_service_5', 'content' => 'Audio Restoration', 'page' => 'about']);
    PageContent::factory()->create(['key' => 'about_service_6', 'content' => 'Podcast Production', 'page' => 'about']);

    // And: Services section is enabled, others disabled
    SectionSetting::factory()->create(['section_key' => 'services', 'page' => 'about', 'is_enabled' => true]);
    SectionSetting::factory()->create(['section_key' => 'experience', 'page' => 'about', 'is_enabled' => false]);
    SectionSetting::factory()->create(['section_key' => 'philosophy', 'page' => 'about', 'is_enabled' => false]);

    $contentService = app(ContentService::class);
    $content = $contentService->getAboutContent();

    $response = $this->get('/about');

    if (isset($content['services'])) {
        foreach ($content['services'] as $service) {
            $response->assertSee($service);
        }
    }
});

test('about page services list uses semantic HTML structure', function () {
    // Given: Minimum content + services exist
    PageContent::factory()->create(['key' => 'about_title', 'content' => 'About Soundscape', 'page' => 'about']);
    PageContent::factory()->create(['key' => 'about_intro', 'content' => 'Professional audio engineer', 'page' => 'about']);
    PageContent::factory()->create(['key' => 'about_bio', 'content' => 'Specializing in mixing and mastering', 'page' => 'about']);

    // And: Services content exists
    PageContent::factory()->create(['key' => 'about_service_1', 'content' => 'Recording', 'page' => 'about']);
    PageContent::factory()->create(['key' => 'about_service_2', 'content' => 'Mixing', 'page' => 'about']);

    // And: Services section is enabled
    SectionSetting::factory()->create(['section_key' => 'services', 'page' => 'about', 'is_enabled' => true]);
    SectionSetting::factory()->create(['section_key' => 'experience', 'page' => 'about', 'is_enabled' => false]);
    SectionSetting::factory()->create(['section_key' => 'philosophy', 'page' => 'about', 'is_enabled' => false]);

    $response = $this->get('/about');

    // Assert: Services list should use semantic <ul> and <li> elements
    $response->assertStatus(200)
        ->assertSeeInOrder(['<ul', '<li', '</li>', '</ul>'], false);
});

test('footer navigation uses semantic HTML structure', function () {
    // Given: Minimum content exists
    PageContent::factory()->create(['key' => 'about_title', 'content' => 'About Soundscape', 'page' => 'about']);
    PageContent::factory()->create(['key' => 'about_intro', 'content' => 'Professional audio engineer', 'page' => 'about']);
    PageContent::factory()->create(['key' => 'about_bio', 'content' => 'Specializing in mixing and mastering', 'page' => 'about']);

    // And: Sections are disabled
    SectionSetting::factory()->create(['section_key' => 'experience', 'page' => 'about', 'is_enabled' => false]);
    SectionSetting::factory()->create(['section_key' => 'services', 'page' => 'about', 'is_enabled' => false]);
    SectionSetting::factory()->create(['section_key' => 'philosophy', 'page' => 'about', 'is_enabled' => false]);

    $response = $this->get('/about');

    // Assert: Footer should use semantic <nav>, <ul>, and <li> elements
    $response->assertStatus(200)
        ->assertSee('aria-label="Footer links"', false)
        ->assertSeeInOrder(['<nav', '<ul', '<li', '</li>', '</ul>', '</nav>'], false);
});
