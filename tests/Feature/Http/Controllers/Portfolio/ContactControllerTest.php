<?php

use App\Application\Portfolio\Services\ContentService;
use App\Models\PageContent;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('contact page is accessible', function () {
    // Given: Minimum content exists
    PageContent::factory()->create(['key' => 'contact_title', 'content' => 'Get in Touch', 'page' => 'contact']);
    PageContent::factory()->create(['key' => 'contact_subtitle', 'content' => 'Let\'s discuss your project', 'page' => 'contact']);
    PageContent::factory()->create(['key' => 'contact_description', 'content' => 'Ready to elevate your audio?', 'page' => 'contact']);
    PageContent::factory()->create(['key' => 'contact_email', 'content' => 'contact@soundscape.audio', 'page' => 'contact']);
    PageContent::factory()->create(['key' => 'contact_phone', 'content' => '+33 6 XX XX XX XX', 'page' => 'contact']);
    PageContent::factory()->create(['key' => 'contact_location', 'content' => 'Paris, France', 'page' => 'contact']);

    $response = $this->get('/contact');

    $response->assertStatus(200);
});

test('contact page contains proper SEO meta tags', function () {
    // Given: Minimum content exists
    PageContent::factory()->create(['key' => 'contact_title', 'content' => 'Get in Touch', 'page' => 'contact']);
    PageContent::factory()->create(['key' => 'contact_subtitle', 'content' => 'Let\'s discuss your project', 'page' => 'contact']);
    PageContent::factory()->create(['key' => 'contact_description', 'content' => 'Ready to elevate your audio?', 'page' => 'contact']);
    PageContent::factory()->create(['key' => 'contact_email', 'content' => 'contact@soundscape.audio', 'page' => 'contact']);
    PageContent::factory()->create(['key' => 'contact_phone', 'content' => '+33 6 XX XX XX XX', 'page' => 'contact']);
    PageContent::factory()->create(['key' => 'contact_location', 'content' => 'Paris, France', 'page' => 'contact']);

    $response = $this->get('/contact');

    $response->assertSee('<title>Contact Soundscape - Get in Touch</title>', false);
    $response->assertSee('<meta name="description"', false);
    $response->assertSee('<meta name="keywords"', false);
});

test('contact page displays content from ContentService', function () {
    // Given: Minimum content exists
    PageContent::factory()->create(['key' => 'contact_title', 'content' => 'Get in Touch', 'page' => 'contact']);
    PageContent::factory()->create(['key' => 'contact_subtitle', 'content' => 'Let\'s discuss your project', 'page' => 'contact']);
    PageContent::factory()->create(['key' => 'contact_description', 'content' => 'Ready to elevate your audio?', 'page' => 'contact']);
    PageContent::factory()->create(['key' => 'contact_email', 'content' => 'contact@soundscape.audio', 'page' => 'contact']);
    PageContent::factory()->create(['key' => 'contact_phone', 'content' => '+33 6 XX XX XX XX', 'page' => 'contact']);
    PageContent::factory()->create(['key' => 'contact_location', 'content' => 'Paris, France', 'page' => 'contact']);

    $contentService = app(ContentService::class);
    $content = $contentService->getContactContent();

    $response = $this->get('/contact');

    $response->assertSee($content['title']);
    $response->assertSee($content['subtitle']);
    $response->assertSee($content['description']);
    $response->assertSee($content['info']['email']);
    $response->assertSee($content['info']['phone']);
    $response->assertSee($content['info']['location']);
});

test('contact page shows contact form', function () {
    // Given: Minimum content exists
    PageContent::factory()->create(['key' => 'contact_title', 'content' => 'Get in Touch', 'page' => 'contact']);
    PageContent::factory()->create(['key' => 'contact_subtitle', 'content' => 'Let\'s discuss your project', 'page' => 'contact']);
    PageContent::factory()->create(['key' => 'contact_description', 'content' => 'Ready to elevate your audio?', 'page' => 'contact']);
    PageContent::factory()->create(['key' => 'contact_email', 'content' => 'contact@soundscape.audio', 'page' => 'contact']);
    PageContent::factory()->create(['key' => 'contact_phone', 'content' => '+33 6 XX XX XX XX', 'page' => 'contact']);
    PageContent::factory()->create(['key' => 'contact_location', 'content' => 'Paris, France', 'page' => 'contact']);

    $response = $this->get('/contact');

    $response->assertSee('wire:model="name"', false);
    $response->assertSee('wire:model="email"', false);
    $response->assertSee('wire:model="subject"', false);
    $response->assertSee('wire:model="message"', false);
    $response->assertSee('wire:model="gdpr_consent"', false);
});
