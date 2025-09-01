<?php

test('home page can be accessed', function () {
    $response = $this->get('/');

    $response->assertStatus(200)
        ->assertViewIs('portfolio.home')
        ->assertSee('Soundscape Audio');
});

test('home page has proper SEO meta tags', function () {
    $response = $this->get('/');

    $response->assertStatus(200)
        ->assertSee('<meta name="description"', false)
        ->assertSee('<meta property="og:title"', false)
        ->assertSee('<meta property="og:description"', false);
});

test('home page displays content from ContentService', function () {
    $response = $this->get('/');

    $response->assertStatus(200)
        ->assertSee('Professional Audio Engineering')
        ->assertSee('Transform your audio projects with industry-standard expertise');
});
