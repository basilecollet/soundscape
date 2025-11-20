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

    assert($recentPos !== false); // Type narrowing for PHPStan
    assert($middlePos !== false);
    assert($oldPos !== false);
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

test('project details page can be accessed with valid slug', function () {
    // Arrange: Create a published project
    $project = ProjectDatabase::factory()
        ->withATitle('My Awesome Project')
        ->withDescription('This is a **complete** description with markdown')
        ->withAShortDescription('Short description')
        ->withAProjectDate(Carbon::parse('2024-06-15'))
        ->published()
        ->create();

    // Act
    $response = $this
        ->get(route('projects.show', ['slug' => $project->slug]));

    // Assert
    $response->assertStatus(200)
        ->assertViewIs('portfolio.project-show');
});

test('project details page returns 404 for non-existent slug', function () {
    // Act
    $response = $this->get('/projects/non-existent-slug');

    // Assert
    $response->assertStatus(404);
});

test('project details page returns 404 for draft project', function () {
    // Arrange: Create a draft project
    $project = ProjectDatabase::factory()
        ->withATitle('Draft Project')
        ->draft()
        ->create();

    // Act
    $response = $this
        ->get(route('projects.show', ['slug' => $project->slug]));

    // Assert
    $response->assertStatus(404);
});

test('project details page returns 404 for archived project', function () {
    // Arrange: Create an archived project
    $project = ProjectDatabase::factory()
        ->withATitle('Archived Project')
        ->withDescription('This project is archived')
        ->archived()
        ->create();

    // Act
    $response = $this
        ->get(route('projects.show', ['slug' => $project->slug]));

    // Assert
    $response->assertStatus(404);
});

test('project details page displays correct project information', function () {
    // Arrange: Create a published project
    $project = ProjectDatabase::factory()
        ->withATitle('Complete Project')
        ->withDescription('This is the **full description** of the project')
        ->withAShortDescription('Short desc')
        ->withAProjectDate(Carbon::parse('2024-06-15'))
        ->published()
        ->create();

    // Act
    $response = $this
        ->get(route('projects.show', ['slug' => $project->slug]));

    // Assert
    $response->assertStatus(200)
        ->assertSee('Complete Project')
        ->assertSee('This is the **full description** of the project')
        ->assertSee('Short desc')
        ->assertSee('2024-06-15');
});

test('project details page has proper SEO meta tags', function () {
    // Arrange: Create a published project
    $project = ProjectDatabase::factory()
        ->withATitle('SEO Test Project')
        ->withDescription('Project description for SEO')
        ->withAShortDescription('Short SEO description')
        ->published()
        ->create();

    // Act
    $response = $this
        ->get(route('projects.show', ['slug' => $project->slug]));

    // Assert
    $response->assertStatus(200)
        ->assertSee('<meta name="description"', false)
        ->assertSee('<meta property="og:title"', false)
        ->assertSee('<meta property="og:description"', false)
        ->assertSee('SEO Test Project', false);
});
