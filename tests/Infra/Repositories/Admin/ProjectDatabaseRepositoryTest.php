<?php

use App\Domain\Admin\Entities\Project;
use App\Domain\Admin\Entities\ValueObjects\ProjectSlug;
use App\Domain\Admin\Exceptions\ProjectNotFoundException;
use App\Infra\Repositories\Admin\ProjectDatabaseRepository;
use App\Models\Project as ProjectDatabase;
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

test('can get all projects from database', function () {
    $repository = new ProjectDatabaseRepository;

    ProjectDatabase::create([
        'title' => 'Project One',
        'slug' => 'project-one',
        'status' => 'draft',
    ]);

    ProjectDatabase::create([
        'title' => 'Project Two',
        'slug' => 'project-two',
        'status' => 'published',
    ]);

    $projects = $repository->getAll();
    $firstProject = $projects->first();
    $lastProject = $projects->last();

    expect($projects)->toHaveCount(2)
        ->and($firstProject)->toBeInstanceOf(Project::class)
        ->and($firstProject)->not->toBeNull()
        ->and((string) $firstProject?->getTitle())->toBe('Project One')
        ->and((string) $lastProject?->getTitle())->toBe('Project Two');

});

test('getAll returns empty collection when no projects', function () {
    $repository = new ProjectDatabaseRepository;

    $projects = $repository->getAll();

    expect($projects)->toBeEmpty();
});

test('getAll reconstitutes project with correct status', function () {
    $repository = new ProjectDatabaseRepository;

    ProjectDatabase::create([
        'title' => 'Published Project',
        'slug' => 'published-project',
        'status' => 'published',
    ]);

    $projects = $repository->getAll();
    $project = $projects->first();

    expect($project?->getStatus()->isPublished())->toBeTrue();
});

test('getAll reconstitutes project with all optional fields', function () {
    $repository = new ProjectDatabaseRepository;

    ProjectDatabase::create([
        'title' => 'Full Project',
        'slug' => 'full-project',
        'status' => 'archived',
        'description' => 'Long description',
        'short_description' => 'Short desc',
        'client_name' => 'acme corporation',
        'project_date' => '2024-06-15',
    ]);

    $projects = $repository->getAll();
    $project = $projects->first();

    expect((string) $project?->getTitle())->toBe('Full Project')
        ->and((string) $project?->getSlug())->toBe('full-project')
        ->and($project?->getStatus()->isArchived())->toBeTrue()
        ->and((string) $project?->getDescription())->toBe('Long description')
        ->and((string) $project?->getShortDescription())->toBe('Short desc')
        ->and((string) $project?->getClientName())->toBe('Acme Corporation')
        ->and($project?->getProjectDate()?->format('Y-m-d'))->toBe('2024-06-15');
});

test('getAll reconstitutes project with null optional fields', function () {
    $repository = new ProjectDatabaseRepository;

    ProjectDatabase::create([
        'title' => 'Minimal Project',
        'slug' => 'minimal-project',
        'status' => 'draft',
        'description' => null,
        'short_description' => null,
        'client_name' => null,
        'project_date' => null,
    ]);

    $projects = $repository->getAll();
    $project = $projects->first();

    expect($project?->getDescription())->toBeNull()
        ->and($project?->getShortDescription())->toBeNull()
        ->and($project?->getClientName())->toBeNull()
        ->and($project?->getProjectDate())->toBeNull();
});

test('findBySlug returns project when slug exists', function () {
    $repository = new ProjectDatabaseRepository;

    ProjectDatabase::create([
        'title' => 'Test Project',
        'slug' => 'test-project',
        'status' => 'draft',
    ]);

    $slug = ProjectSlug::fromString('test-project');
    $project = $repository->findBySlug($slug);

    expect($project)->toBeInstanceOf(Project::class)
        ->and((string) $project?->getTitle())->toBe('Test Project')
        ->and((string) $project?->getSlug())->toBe('test-project');
});

test('findBySlug returns null when slug does not exist', function () {
    $repository = new ProjectDatabaseRepository;

    $slug = ProjectSlug::fromString('non-existent');
    $project = $repository->findBySlug($slug);

    expect($project)->toBeNull();
});

test('findBySlug reconstitutes project with all fields', function () {
    $repository = new ProjectDatabaseRepository;

    ProjectDatabase::create([
        'title' => 'Complete Project',
        'slug' => 'complete-project',
        'status' => 'published',
        'description' => 'Full description',
        'short_description' => 'Brief',
        'client_name' => 'test client',
        'project_date' => '2024-07-20',
    ]);

    $slug = ProjectSlug::fromString('complete-project');
    $project = $repository->findBySlug($slug);

    expect($project)->toBeInstanceOf(Project::class)
        ->and((string) $project?->getDescription())->toBe('Full description')
        ->and((string) $project?->getShortDescription())->toBe('Brief')
        ->and((string) $project?->getClientName())->toBe('Test Client')
        ->and($project?->getProjectDate()?->format('Y-m-d'))->toBe('2024-07-20')
        ->and($project?->getStatus()->isPublished())->toBeTrue();
});

test('getBySlug returns project when slug exists', function () {
    $repository = new ProjectDatabaseRepository;

    ProjectDatabase::create([
        'title' => 'Found Project',
        'slug' => 'found-project',
        'status' => 'draft',
    ]);

    $slug = ProjectSlug::fromString('found-project');
    $project = $repository->getBySlug($slug);

    expect($project)->toBeInstanceOf(Project::class)
        ->and((string) $project->getTitle())->toBe('Found Project')
        ->and((string) $project->getSlug())->toBe('found-project');
});

test('getBySlug throws exception when slug does not exist', function () {
    $repository = new ProjectDatabaseRepository;

    $slug = ProjectSlug::fromString('non-existent-project');

    expect(fn () => $repository->getBySlug($slug))
        ->toThrow(ProjectNotFoundException::class);
});
