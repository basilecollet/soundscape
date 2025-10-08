<?php

use App\Domain\Admin\Entities\Project;
use App\Infra\Repositories\Admin\ProjectDatabaseRepository;
use App\Models\Project as ProjectDatabase;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('can store a project from the domain entity', function () {
    $domainProject = Project::new('My project');

    $repository = new ProjectDatabaseRepository;

    $repository->store($domainProject);

    $this->assertDatabaseHas('projects', [
        'title' => 'My project',
        'slug' => 'my-project',
    ]);
});

test('stores project with timestamps', function () {
    $project = Project::new('My project');
    $repository = new ProjectDatabaseRepository;

    $repository->store($project);

    $storedProject = ProjectDatabase::where('slug', 'my-project')->firstOrFail();

    expect($storedProject->created_at)->not->toBeNull()
        ->and($storedProject->updated_at)->not->toBeNull();
});

test('cannot store two projects with the same slug', function () {
    $repository = new ProjectDatabaseRepository;

    $repository->store(Project::new('My project'));

    expect(fn () => $repository->store(Project::new('My project')))
        ->toThrow(QueryException::class);
});

test('stores project with special characters in title', function () {
    $project = Project::new('Été à Paris! Très spécial & unique');
    $repository = new ProjectDatabaseRepository;

    $repository->store($project);

    $this->assertDatabaseHas('projects', [
        'title' => 'Été à Paris! Très spécial & unique',
        'slug' => 'ete-a-paris-tres-special-unique',
    ]);
});

test('can store multiple projects with different slugs', function () {
    $repository = new ProjectDatabaseRepository;

    $repository->store(Project::new('First project'));
    $repository->store(Project::new('Second project'));
    $repository->store(Project::new('Third project'));

    expect(ProjectDatabase::count())->toBe(3);
});
