<?php

use App\Application\Portfolio\Services\ContentService;

test('contact page is accessible', function () {
    $response = $this->get('/contact');

    $response->assertStatus(200);
});

test('contact page contains proper SEO meta tags', function () {
    $response = $this->get('/contact');

    $response->assertSee('<title>Contact Soundscape - Get in Touch</title>', false);
    $response->assertSee('<meta name="description"', false);
    $response->assertSee('<meta name="keywords"', false);
});

test('contact page displays content from ContentService', function () {
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
    $response = $this->get('/contact');

    $response->assertSee('name="name"', false);
    $response->assertSee('name="email"', false);
    $response->assertSee('name="subject"', false);
    $response->assertSee('name="message"', false);
    $response->assertSee('name="gdpr_consent"', false);
});