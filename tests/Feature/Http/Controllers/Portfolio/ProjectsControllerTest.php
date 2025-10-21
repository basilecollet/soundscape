<?php

use App\Models\Project as ProjectDatabase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;

uses(RefreshDatabase::class);

test('projects page can be accessed', function () {
    $response = $this->get('/projects');

    $response->assertStatus(200)
        ->assertViewIs('portfolio.projects');
});

test('projects page has proper SEO meta tags', function () {
    $response = $this->get('/projects');

    $response->assertStatus(200)
        ->assertSee('<meta name="description"', false)
        ->assertSee('<meta property="og:title"', false)
        ->assertSee('<meta property="og:description"', false);
});

test('projects page displays only published projects', function () {
    // Arrange: Create projects with different statuses
    ProjectDatabase::factory()
        ->withATitle('Draft Project')
        ->draft()
        ->create();

    ProjectDatabase::factory()
        ->withATitle('Published Project 1')
        ->withAProjectDate(Carbon::parse('2024-06-15'))
        ->published()
        ->create();

    ProjectDatabase::factory()
        ->withATitle('Published Project 2')
        ->withAProjectDate(Carbon::parse('2024-06-20'))
        ->published()
        ->create();

    ProjectDatabase::factory()
        ->withATitle('Archived Project')
        ->archived()
        ->create();

    // Act
    $response = $this->get('/projects');

    // Assert: Should only see published projects
    $response->assertStatus(200)
        ->assertSee('Published Project 1')
        ->assertSee('Published Project 2')
        ->assertDontSee('Draft Project')
        ->assertDontSee('Archived Project');
});

test('projects page displays projects ordered by date descending', function () {
    // Arrange: Create published projects with different dates
    ProjectDatabase::factory()
        ->withATitle('Old Project')
        ->withAProjectDate(Carbon::parse('2024-01-01'))
        ->published()
        ->create();

    ProjectDatabase::factory()
        ->withATitle('Recent Project')
        ->withAProjectDate(Carbon::parse('2024-12-01'))
        ->published()
        ->create();

    ProjectDatabase::factory()
        ->withATitle('Middle Project')
        ->withAProjectDate(Carbon::parse('2024-06-01'))
        ->published()
        ->create();

    // Act
    $response = $this->get('/projects');

    // Assert: Check order in response content
    $response->assertStatus(200);

    $content = $response->getContent();
    $recentPos = strpos($content, 'Recent Project');
    $middlePos = strpos($content, 'Middle Project');
    $oldPos = strpos($content, 'Old Project');

    expect($recentPos)->toBeLessThan($middlePos)
        ->and($middlePos)->toBeLessThan($oldPos);
});

test('projects page shows empty state when no published projects', function () {
    // Arrange: Create only non-published projects
    ProjectDatabase::factory()
        ->withATitle('Draft Project')
        ->draft()
        ->create();

    // Act
    $response = $this->get('/projects');

    // Assert
    $response->assertStatus(200)
        ->assertDontSee('Draft Project');
});