<?php

declare(strict_types=1);

use App\Domain\Admin\Entities\Project;
use App\Domain\Admin\Entities\ValueObjects\ProjectDescription;
use App\Domain\Admin\Entities\ValueObjects\ProjectSlug;
use App\Domain\Admin\Entities\ValueObjects\ProjectTitle;

test('create a project with the right data', function () {
    $project = Project::new('My project');

    expect($project)->toBeInstanceOf(Project::class)
        ->and($project->getTitle())->toBeInstanceOf(ProjectTitle::class)
        ->and($project->getTitle())->toEqual(ProjectTitle::fromString('My project'));
});

test('project automatically generates slug from title', function () {
    $project = Project::new('My Awesome Project');

    expect($project->getSlug())->toBeInstanceOf(ProjectSlug::class)
        ->and((string) $project->getSlug())->toBe('my-awesome-project');
});

test('project slug handles special characters', function () {
    $project = Project::new('Café & Restaurant 2024');

    expect((string) $project->getSlug())->toBe('cafe-restaurant-2024');
});

test('project toArray includes slug', function () {
    $project = Project::new('My Project');
    $array = $project->toArray();

    expect($array)->toHaveKey('title')
        ->and($array)->toHaveKey('slug')
        ->and($array['title'])->toBe('My Project')
        ->and($array['slug'])->toBe('my-project');
});

test('new project has draft status by default', function () {
    $project = Project::new('My Project');

    expect($project->getStatus()->isDraft())->toBeTrue();
});

test('project can be published', function () {
    $project = Project::new('My Project');

    $project->publish();

    expect($project->getStatus()->isPublished())->toBeTrue();
});

test('project can be archived', function () {
    $project = Project::new('My Project');

    $project->archive();

    expect($project->getStatus()->isArchived())->toBeTrue();
});

test('project can be set back to draft', function () {
    $project = Project::new('My Project');
    $project->publish();

    $project->draft();

    expect($project->getStatus()->isDraft())->toBeTrue();
});

test('project toArray includes status', function () {
    $project = Project::new('My Project');
    $array = $project->toArray();

    expect($array)->toHaveKey('status')
        ->and($array['status'])->toBe('draft');
});

test('published project toArray has published status', function () {
    $project = Project::new('My Project');

    $project->publish();
    $array = $project->toArray();

    expect($array['status'])->toBe('published');
});

test('project can have a description', function () {
    $project = Project::new('My Project', 'This is a **markdown** description');

    expect($project->getDescription())->toBeInstanceOf(ProjectDescription::class)
        ->and((string) $project->getDescription())->toBe('This is a **markdown** description');
});

test('project can have null description', function () {
    $project = Project::new('My Project');

    expect($project->getDescription())->toBeNull();
});

test('project toArray includes description', function () {
    $project = Project::new('My Project', 'My description');
    $array = $project->toArray();

    expect($array)->toHaveKey('description')
        ->and($array['description'])->toBe('My description');
});

test('project toArray has null description when not set', function () {
    $project = Project::new('My Project');
    $array = $project->toArray();

    expect($array)->toHaveKey('description')
        ->and($array['description'])->toBeNull();
});

test('project can have a short description', function () {
    $project = Project::new('My Project', null, 'A brief summary');

    expect($project->getShortDescription())->not->toBeNull()
        ->and((string) $project->getShortDescription())->toBe('A brief summary');
});

test('project can have null short description', function () {
    $project = Project::new('My Project');

    expect($project->getShortDescription())->toBeNull();
});

test('project can have a client name', function () {
    $project = Project::new('My Project', null, null, 'Acme Corporation');

    expect($project->getClientName())->not->toBeNull()
        ->and((string) $project->getClientName())->toBe('Acme Corporation');
});

test('project can have null client name', function () {
    $project = Project::new('My Project');

    expect($project->getClientName())->toBeNull();
});

test('project can have a project date', function () {
    $project = Project::new('My Project', null, null, null, '2024-06-15');
    $projectDate = $project->getProjectDate();

    expect($projectDate)->not->toBeNull()
        ->and($projectDate?->format('Y-m-d'))->toBe('2024-06-15');
});

test('project can have null project date', function () {
    $project = Project::new('My Project');

    expect($project->getProjectDate())->toBeNull();
});

test('project toArray includes all optional fields', function () {
    $project = Project::new('My Project', 'Long description', 'Short desc', 'Client Inc', '2024-06-15');
    $array = $project->toArray();

    expect($array)->toHaveKey('short_description')
        ->and($array)->toHaveKey('client_name')
        ->and($array)->toHaveKey('project_date')
        ->and($array['short_description'])->toBe('Short desc')
        ->and($array['client_name'])->toBe('Client Inc')
        ->and($array['project_date'])->toBe('2024-06-15');
});
