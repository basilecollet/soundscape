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

test('stores project with draft status by default', function () {
    $project = Project::new('My Project');
    $repository = new ProjectDatabaseRepository;

    $repository->store($project);

    $this->assertDatabaseHas('projects', [
        'title' => 'My Project',
        'slug' => 'my-project',
        'status' => 'draft',
    ]);
});

test('stores project with published status', function () {
    $project = Project::new('My Project');
    $project->publish();
    $repository = new ProjectDatabaseRepository;

    $repository->store($project);

    $this->assertDatabaseHas('projects', [
        'title' => 'My Project',
        'slug' => 'my-project',
        'status' => 'published',
    ]);
});

test('stores project with archived status', function () {
    $project = Project::new('My Project');
    $project->archive();
    $repository = new ProjectDatabaseRepository;

    $repository->store($project);

    $this->assertDatabaseHas('projects', [
        'title' => 'My Project',
        'slug' => 'my-project',
        'status' => 'archived',
    ]);
});

test('stores project with description', function () {
    $project = Project::new('My Project', 'This is a **markdown** description');
    $repository = new ProjectDatabaseRepository;

    $repository->store($project);

    $this->assertDatabaseHas('projects', [
        'title' => 'My Project',
        'slug' => 'my-project',
        'description' => 'This is a **markdown** description',
    ]);
});

test('stores project with null description', function () {
    $project = Project::new('My Project');
    $repository = new ProjectDatabaseRepository;

    $repository->store($project);

    $this->assertDatabaseHas('projects', [
        'title' => 'My Project',
        'slug' => 'my-project',
        'description' => null,
    ]);
});

test('stores project with short description', function () {
    $project = Project::new('My Project', null, 'A brief summary');
    $repository = new ProjectDatabaseRepository;

    $repository->store($project);

    $this->assertDatabaseHas('projects', [
        'title' => 'My Project',
        'slug' => 'my-project',
        'short_description' => 'A brief summary',
    ]);
});

test('stores project with null short description', function () {
    $project = Project::new('My Project');
    $repository = new ProjectDatabaseRepository;

    $repository->store($project);

    $this->assertDatabaseHas('projects', [
        'title' => 'My Project',
        'slug' => 'my-project',
        'short_description' => null,
    ]);
});

test('stores project with client name', function () {
    $project = Project::new('My Project', null, null, 'Acme Corporation');
    $repository = new ProjectDatabaseRepository;

    $repository->store($project);

    $this->assertDatabaseHas('projects', [
        'title' => 'My Project',
        'slug' => 'my-project',
        'client_name' => 'Acme Corporation',
    ]);
});

test('stores project with null client name', function () {
    $project = Project::new('My Project');
    $repository = new ProjectDatabaseRepository;

    $repository->store($project);

    $this->assertDatabaseHas('projects', [
        'title' => 'My Project',
        'slug' => 'my-project',
        'client_name' => null,
    ]);
});

test('stores project with project date', function () {
    $project = Project::new('My Project', null, null, null, '2024-06-15');
    $repository = new ProjectDatabaseRepository;

    $repository->store($project);

    $this->assertDatabaseHas('projects', [
        'title' => 'My Project',
        'slug' => 'my-project',
        'project_date' => '2024-06-15',
    ]);
});

test('stores project with null project date', function () {
    $project = Project::new('My Project');
    $repository = new ProjectDatabaseRepository;

    $repository->store($project);

    $this->assertDatabaseHas('projects', [
        'title' => 'My Project',
        'slug' => 'my-project',
        'project_date' => null,
    ]);
});

test('stores project with all optional fields', function () {
    $project = Project::new(
        'My Project',
        'Long description',
        'Short desc',
        'Client Inc',
        '2024-06-15'
    );
    $repository = new ProjectDatabaseRepository;

    $repository->store($project);

    $this->assertDatabaseHas('projects', [
        'title' => 'My Project',
        'slug' => 'my-project',
        'description' => 'Long description',
        'short_description' => 'Short desc',
        'client_name' => 'Client Inc',
        'project_date' => '2024-06-15',
    ]);
});
