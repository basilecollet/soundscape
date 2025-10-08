<?php

declare(strict_types=1);

use App\Application\Admin\Commands\CreateProject\CreateProjectHandler;
use App\Application\Admin\DTOs\CreateProjectData;
use App\Domain\Admin\Entities\ValueObjects\ProjectSlug;
use App\Infra\Repositories\Admin\ProjectDatabaseRepository;

test('can create project with only title', function () {
    $repository = Mockery::mock(ProjectDatabaseRepository::class);
    $repository->shouldReceive('store')->once();

    $handler = new CreateProjectHandler($repository);

    $data = CreateProjectData::fromArray([
        'title' => 'My Project',
    ]);

    $slug = $handler->handle($data);

    expect($slug)->toBeInstanceOf(ProjectSlug::class)
        ->and((string) $slug)->toBe('my-project');
});

test('can create project with all fields', function () {
    $repository = Mockery::mock(ProjectDatabaseRepository::class);
    $repository->shouldReceive('store')->once();

    $handler = new CreateProjectHandler($repository);

    $data = CreateProjectData::fromArray([
        'title' => 'My Awesome Project',
        'description' => 'Long description',
        'short_description' => 'Short desc',
        'client_name' => 'acme corporation',
        'project_date' => '2024-06-15',
    ]);

    $slug = $handler->handle($data);

    expect($slug)->toBeInstanceOf(ProjectSlug::class)
        ->and((string) $slug)->toBe('my-awesome-project');
});

test('handler calls repository to store project', function () {
    $repository = Mockery::mock(ProjectDatabaseRepository::class);
    $repository->shouldReceive('store')
        ->once()
        ->withArgs(function ($project) {
            return (string) $project->getTitle() === 'Test Project'
                && (string) $project->getSlug() === 'test-project'
                && $project->getStatus()->isDraft();
        });

    $handler = new CreateProjectHandler($repository);

    $data = CreateProjectData::fromArray([
        'title' => 'Test Project',
    ]);

    $handler->handle($data);
});

test('handler creates project with correct optional fields', function () {
    $repository = Mockery::mock(ProjectDatabaseRepository::class);
    $repository->shouldReceive('store')
        ->once()
        ->withArgs(function ($project) {
            return (string) $project->getDescription() === 'Description'
                && (string) $project->getShortDescription() === 'Short'
                && (string) $project->getClientName() === 'Acme Corporation'
                && $project->getProjectDate()?->format('Y-m-d') === '2024-06-15';
        });

    $handler = new CreateProjectHandler($repository);

    $data = CreateProjectData::fromArray([
        'title' => 'Project',
        'description' => 'Description',
        'short_description' => 'Short',
        'client_name' => 'acme corporation',
        'project_date' => '2024-06-15',
    ]);

    $handler->handle($data);
});
