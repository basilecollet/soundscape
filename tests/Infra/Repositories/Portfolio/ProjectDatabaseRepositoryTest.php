<?php

use App\Domain\Portfolio\Entities\PublishedProject;
use App\Infra\Repositories\Portfolio\ProjectDatabaseRepository;
use App\Models\Project as ProjectDatabase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;

uses(RefreshDatabase::class);

test('can get all published projects', function () {
    // Arrange: Create some projects with different statuses
    ProjectDatabase::factory()
        ->withATitle('Draft Project')
        ->withAProjectDate(Carbon::parse('2024-06-01'))
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
        ->withAProjectDate(Carbon::parse('2024-06-25'))
        ->archived()
        ->create();

    // Act
    $repository = new ProjectDatabaseRepository;
    $projects = $repository->getAll();

    // Assert: Only published projects should be returned
    expect($projects)->toHaveCount(2)
        ->and($projects[0])->toBeInstanceOf(PublishedProject::class);

    assert($projects[0] instanceof PublishedProject); // Type narrowing for PHPStan
    assert($projects[1] instanceof PublishedProject);
    expect((string) $projects[0]->getTitle())->toBe('Published Project 2')
        ->and((string) $projects[1]->getTitle())->toBe('Published Project 1');
});

test('returns empty collection when no published projects exist', function () {
    // Arrange: Create only draft projects
    ProjectDatabase::factory()
        ->draft()
        ->create();

    // Act
    $repository = new ProjectDatabaseRepository;
    $projects = $repository->getAll();

    // Assert
    expect($projects)->toBeEmpty();
});

test('published projects are ordered by date descending', function () {
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
    $repository = new ProjectDatabaseRepository;
    $projects = $repository->getAll();

    // Assert: Most recent first
    expect($projects)->toHaveCount(3);

    assert($projects[0] instanceof PublishedProject); // Type narrowing for PHPStan
    assert($projects[1] instanceof PublishedProject);
    assert($projects[2] instanceof PublishedProject);
    expect((string) $projects[0]->getTitle())->toBe('Recent Project')
        ->and((string) $projects[1]->getTitle())->toBe('Middle Project')
        ->and((string) $projects[2]->getTitle())->toBe('Old Project');
});

test('transforms database model to PublishedProject entity correctly', function () {
    // Arrange
    ProjectDatabase::factory()
        ->withATitle('Complete Project')
        ->withAProjectDate(Carbon::parse('2024-06-15'))
        ->withAShortDescription('A short description')
        ->published()
        ->create();

    // Act
    $repository = new ProjectDatabaseRepository;
    $projects = $repository->getAll();

    // Assert
    $project = $projects->first();
    expect($project)->toBeInstanceOf(PublishedProject::class);

    assert($project instanceof PublishedProject); // Type narrowing for PHPStan
    expect((string) $project->getTitle())->toBe('Complete Project')
        ->and((string) $project->getSlug())->toBe('complete-project')
        ->and((string) $project->getShortDescription())->toBe('A short description');

    $projectDate = $project->getProjectDate();
    assert($projectDate !== null); // Type narrowing for PHPStan
    expect($projectDate->format('Y-m-d'))->toBe('2024-06-15');
});
