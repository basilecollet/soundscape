<?php

declare(strict_types=1);

use App\Application\Admin\DTOs\ProjectData;
use App\Application\Admin\Queries\GetProjects\GetProjectsHandler;
use App\Domain\Admin\Entities\Project;
use App\Infra\Repositories\Admin\ProjectDatabaseRepository;
use Illuminate\Support\Collection;

test('can get all projects', function () {
    $project1 = Project::new('Project One');
    $project2 = Project::new('Project Two');

    /** @var ProjectDatabaseRepository&\Mockery\MockInterface $repository */
    $repository = Mockery::mock(ProjectDatabaseRepository::class);
    /** @phpstan-ignore method.notFound */
    $repository->shouldReceive('getAll')
        ->once()
        ->andReturn(collect([$project1, $project2]));

    $handler = new GetProjectsHandler($repository);
    $projects = $handler->handle();

    expect($projects)->toBeInstanceOf(Collection::class)
        ->and($projects)->toHaveCount(2)
        ->and($projects->first())->toBeInstanceOf(ProjectData::class)
        ->and($projects->first()?->title)->toBe('Project One')
        ->and($projects->last()?->title)->toBe('Project Two');
});

test('returns empty collection when no projects', function () {
    /** @var ProjectDatabaseRepository&\Mockery\MockInterface $repository */
    $repository = Mockery::mock(ProjectDatabaseRepository::class);
    /** @phpstan-ignore method.notFound */
    $repository->shouldReceive('getAll')
        ->once()
        ->andReturn(collect());

    $handler = new GetProjectsHandler($repository);
    $projects = $handler->handle();

    expect($projects)->toBeInstanceOf(Collection::class)
        ->and($projects)->toBeEmpty();
});

test('transforms all project fields correctly', function () {
    $project = Project::new(
        'My Project',
        'Description',
        'Short desc',
        'client name',
        '2024-06-15'
    );
    $project->publish();

    /** @var ProjectDatabaseRepository&\Mockery\MockInterface $repository */
    $repository = Mockery::mock(ProjectDatabaseRepository::class);
    /** @phpstan-ignore method.notFound */
    $repository->shouldReceive('getAll')
        ->once()
        ->andReturn(collect([$project]));

    $handler = new GetProjectsHandler($repository);
    $projects = $handler->handle();

    $data = $projects->first();

    expect($data?->title)->toBe('My Project')
        ->and($data?->slug)->toBe('my-project')
        ->and($data?->status)->toBe('published')
        ->and($data?->description)->toBe('Description')
        ->and($data?->shortDescription)->toBe('Short desc')
        ->and($data?->clientName)->toBe('Client Name')
        ->and($data?->projectDate)->toBe('2024-06-15');
});
