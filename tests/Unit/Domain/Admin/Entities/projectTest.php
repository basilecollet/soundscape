<?php

declare(strict_types=1);

use App\Domain\Admin\Entities\Enums\ProjectStatus;
use App\Domain\Admin\Entities\Project;
use App\Domain\Admin\Entities\ValueObjects\ClientName;
use App\Domain\Admin\Entities\ValueObjects\ProjectDate;
use App\Domain\Admin\Entities\ValueObjects\ProjectDescription;
use App\Domain\Admin\Entities\ValueObjects\ProjectShortDescription;
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

test('can reconstitute project with minimal fields', function () {
    $project = Project::reconstitute(
        title: ProjectTitle::fromString('Existing Project'),
        slug: ProjectSlug::fromString('existing-project'),
        status: ProjectStatus::Draft,
    );

    expect($project)->toBeInstanceOf(Project::class)
        ->and((string) $project->getTitle())->toBe('Existing Project')
        ->and((string) $project->getSlug())->toBe('existing-project')
        ->and($project->getStatus())->toBe(ProjectStatus::Draft);
});

test('can reconstitute project with published status', function () {
    $project = Project::reconstitute(
        title: ProjectTitle::fromString('Published Project'),
        slug: ProjectSlug::fromString('published-project'),
        status: ProjectStatus::Published,
    );

    expect($project->getStatus()->isPublished())->toBeTrue();
});

test('can reconstitute project with all fields', function () {
    $project = Project::reconstitute(
        title: ProjectTitle::fromString('Full Project'),
        slug: ProjectSlug::fromString('full-project'),
        status: ProjectStatus::Archived,
        description: ProjectDescription::fromString('Description'),
        shortDescription: ProjectShortDescription::fromString('Short'),
        clientName: ClientName::fromString('acme inc'),
        projectDate: ProjectDate::fromString('2024-06-15'),
    );

    expect((string) $project->getTitle())->toBe('Full Project')
        ->and($project->getStatus()->isArchived())->toBeTrue()
        ->and((string) $project->getDescription())->toBe('Description')
        ->and((string) $project->getShortDescription())->toBe('Short')
        ->and((string) $project->getClientName())->toBe('Acme Inc')
        ->and($project->getProjectDate()?->format('Y-m-d'))->toBe('2024-06-15');
});

test('can update project title', function () {
    $project = Project::new('Original Title');

    $project->update(
        title: ProjectTitle::fromString('Updated Title'),
    );

    expect((string) $project->getTitle())->toBe('Updated Title');
});

test('updating title does not change slug', function () {
    $project = Project::new('Original Title');
    $originalSlug = (string) $project->getSlug();

    $project->update(
        title: ProjectTitle::fromString('Completely New Title'),
    );

    expect((string) $project->getSlug())->toBe($originalSlug)
        ->and((string) $project->getSlug())->toBe('original-title');
});

test('can update all optional fields', function () {
    $project = Project::new('My Project');

    $project->update(
        title: ProjectTitle::fromString('My Project'),
        description: ProjectDescription::fromString('New description'),
        shortDescription: ProjectShortDescription::fromString('New short'),
        clientName: ClientName::fromString('new client'),
        projectDate: ProjectDate::fromString('2024-12-25'),
    );

    expect((string) $project->getDescription())->toBe('New description')
        ->and((string) $project->getShortDescription())->toBe('New short')
        ->and((string) $project->getClientName())->toBe('New Client')
        ->and($project->getProjectDate()?->format('Y-m-d'))->toBe('2024-12-25');
});

test('can reset optional fields to null on update', function () {
    $project = Project::new('My Project', 'Description', 'Short', 'Client', '2024-06-15');

    $project->update(
        title: ProjectTitle::fromString('My Project'),
        description: null,
        shortDescription: null,
        clientName: null,
        projectDate: null,
    );

    expect($project->getDescription())->toBeNull()
        ->and($project->getShortDescription())->toBeNull()
        ->and($project->getClientName())->toBeNull()
        ->and($project->getProjectDate())->toBeNull();
});

test('updating project does not change status', function () {
    $project = Project::new('My Project');
    $project->publish();

    $project->update(
        title: ProjectTitle::fromString('Updated Title'),
    );

    expect($project->getStatus()->isPublished())->toBeTrue();
});
