<?php

use App\Models\Project as ProjectDatabase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
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
        ->get(route('projects.show', ['project' => $project->slug]));

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
        ->get(route('projects.show', ['project' => $project->slug]));

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
        ->get(route('projects.show', ['project' => $project->slug]));

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
        ->get(route('projects.show', ['project' => $project->slug]));

    // Assert: Markdown **text** is converted to <strong>text</strong>
    $response->assertStatus(200)
        ->assertSee('Complete Project')
        ->assertSee('This is the', false) // Check for text content
        ->assertSee('full description', false) // Markdown bold is now <strong>
        ->assertSee('of the project')
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
        ->get(route('projects.show', ['project' => $project->slug]));

    // Assert
    $response->assertStatus(200)
        ->assertSee('<meta name="description"', false)
        ->assertSee('<meta property="og:title"', false)
        ->assertSee('<meta property="og:description"', false)
        ->assertSee('SEO Test Project', false);
});

test('project details page escapes HTML in markdown description', function () {
    // Arrange: Create project with potentially malicious HTML in description
    $project = ProjectDatabase::factory()
        ->withATitle('XSS Test Project')
        ->withDescription('**Bold text** and <script>alert("xss")</script> and <img src=x onerror=alert(1)>')
        ->published()
        ->create();

    // Act
    $response = $this
        ->get(route('projects.show', ['project' => $project->slug]));

    // Assert: HTML should be escaped, not executed
    $response->assertStatus(200)
        ->assertDontSee('<script>alert("xss")</script>', false) // Raw script should not appear
        ->assertDontSee('<img src=x onerror=alert(1)>', false) // Raw img tag should not appear
        ->assertSee('&lt;script&gt;', false) // Should be escaped
        ->assertSee('Bold text') // Markdown should still work
        ->assertSee('<strong>Bold text</strong>', false); // Markdown rendered correctly
});

test('projects page hides decorative SVG icons from screen readers', function () {
    // Act
    $response = $this->get('/projects');

    // Assert: SVG icons should have aria-hidden="true"
    $response->assertStatus(200)
        ->assertSee('aria-hidden="true"', false);
});

test('project details page hides decorative SVG icons from screen readers', function () {
    // Arrange: Create a published project
    $project = ProjectDatabase::factory()
        ->withATitle('Accessibility Test Project')
        ->published()
        ->create();

    // Act
    $response = $this->get(route('projects.show', ['project' => $project->slug]));

    // Assert: SVG icons should have aria-hidden="true"
    $response->assertStatus(200)
        ->assertSee('aria-hidden="true"', false);
});

test('project gallery images have descriptive alt text with position', function () {
    // Arrange: Create a published project with gallery images
    $project = ProjectDatabase::factory()
        ->withATitle('Gallery Test Project')
        ->published()
        ->create();

    // Add 3 gallery images
    $project->addMedia(UploadedFile::fake()->image('gallery1.jpg'))
        ->toMediaCollection('gallery');
    $project->addMedia(UploadedFile::fake()->image('gallery2.jpg'))
        ->toMediaCollection('gallery');
    $project->addMedia(UploadedFile::fake()->image('gallery3.jpg'))
        ->toMediaCollection('gallery');

    // Act
    $response = $this->get(route('projects.show', ['project' => $project->slug]));

    // Assert: Gallery images should have descriptive alt text with position (FR locale)
    $response->assertStatus(200)
        ->assertSee('Image 1 de la galerie du projet Gallery Test Project')
        ->assertSee('Image 2 de la galerie du projet Gallery Test Project')
        ->assertSee('Image 3 de la galerie du projet Gallery Test Project');
});
