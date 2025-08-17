<?php

use App\Application\Portfolio\Services\ContentService;

test('about page is accessible', function () {
    $response = $this->get('/about');

    $response->assertStatus(200);
});

test('about page contains proper SEO meta tags', function () {
    $response = $this->get('/about');

    $response->assertSee('<title>About Soundscape - Professional Audio Engineering</title>', false);
    $response->assertSee('<meta name="description"', false);
    $response->assertSee('<meta name="keywords"', false);
});

test('about page displays content from ContentService', function () {
    $contentService = app(ContentService::class);
    $content = $contentService->getAboutContent();

    $response = $this->get('/about');

    $response->assertSee($content['title']);
    $response->assertSee($content['intro']);
    $response->assertSee($content['bio']);
    $response->assertSee($content['experience']['years']);
    $response->assertSee($content['experience']['projects']);
    $response->assertSee($content['experience']['clients']);
});

test('about page shows all services', function () {
    $contentService = app(ContentService::class);
    $content = $contentService->getAboutContent();

    $response = $this->get('/about');

    foreach ($content['services'] as $service) {
        $response->assertSee($service);
    }
});