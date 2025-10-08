<?php

declare(strict_types=1);

use App\Domain\Admin\Entities\Project;
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
