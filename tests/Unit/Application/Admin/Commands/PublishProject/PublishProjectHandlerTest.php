<?php

declare(strict_types=1);

use App\Application\Admin\Commands\PublishProject\PublishProjectHandler;
use App\Domain\Admin\Entities\Enums\ProjectStatus;
use App\Domain\Admin\Entities\Project;
use App\Domain\Admin\Entities\ValueObjects\ProjectSlug;
use App\Domain\Admin\Entities\ValueObjects\ProjectTitle;
use App\Domain\Admin\Exceptions\ProjectCannotBePublishedException;
use App\Domain\Admin\Exceptions\ProjectNotFoundException;
use App\Infra\Repositories\Admin\ProjectDatabaseRepository;

test('can publish a draft project', function () {
    /** @var ProjectDatabaseRepository&\Mockery\MockInterface $repository */
    $repository = Mockery::mock(ProjectDatabaseRepository::class);

    // Mock : récupérer un projet Draft
    $project = Project::reconstitute(
        title: ProjectTitle::fromString('My Project'),
        slug: ProjectSlug::fromString('my-project'),
        status: ProjectStatus::Draft
    );

    /** @phpstan-ignore method.notFound */
    $repository->shouldReceive('getBySlug')
        ->once()
        ->with(Mockery::on(fn ($arg) => $arg instanceof ProjectSlug && (string) $arg === 'my-project'))
        ->andReturn($project);

    /** @phpstan-ignore method.notFound */
    $repository->shouldReceive('store')
        ->once()
        ->with(Mockery::on(function ($arg) {
            return $arg instanceof Project
                && $arg->getStatus() === ProjectStatus::Published;
        }))
        ->andReturnNull();

    $handler = new PublishProjectHandler($repository);

    $handler->handle('my-project');

    expect($project->getStatus())->toBe(ProjectStatus::Published);
});

test('handler throws exception when project not found', function () {
    /** @var ProjectDatabaseRepository&\Mockery\MockInterface $repository */
    $repository = Mockery::mock(ProjectDatabaseRepository::class);

    /** @phpstan-ignore method.notFound */
    $repository->shouldReceive('getBySlug')
        ->once()
        ->andThrow(ProjectNotFoundException::forSlug(ProjectSlug::fromString('non-existent')));

    $handler = new PublishProjectHandler($repository);

    expect(fn () => $handler->handle('non-existent'))
        ->toThrow(ProjectNotFoundException::class);
});

test('cannot publish an archived project', function () {
    /** @var ProjectDatabaseRepository&\Mockery\MockInterface $repository */
    $repository = Mockery::mock(ProjectDatabaseRepository::class);

    // Mock : projet Archived
    $project = Project::reconstitute(
        title: ProjectTitle::fromString('Archived Project'),
        slug: ProjectSlug::fromString('archived-project'),
        status: ProjectStatus::Archived
    );

    /** @phpstan-ignore method.notFound */
    $repository->shouldReceive('getBySlug')
        ->once()
        ->andReturn($project);

    // store should NOT be called because an exception will be thrown
    $repository->shouldNotReceive('store');

    $handler = new PublishProjectHandler($repository);

    expect(fn () => $handler->handle('archived-project'))
        ->toThrow(ProjectCannotBePublishedException::class);
});

test('handler calls repository methods correctly', function () {
    /** @var ProjectDatabaseRepository&\Mockery\MockInterface $repository */
    $repository = Mockery::mock(ProjectDatabaseRepository::class);

    $project = Project::reconstitute(
        title: ProjectTitle::fromString('Test Project'),
        slug: ProjectSlug::fromString('test-project'),
        status: ProjectStatus::Draft
    );

    /** @phpstan-ignore method.notFound */
    $repository->shouldReceive('getBySlug')
        ->once()
        ->andReturn($project);

    /** @phpstan-ignore method.notFound */
    $repository->shouldReceive('store')
        ->once()
        ->with($project)
        ->andReturnNull();

    $handler = new PublishProjectHandler($repository);

    $handler->handle('test-project');
});
