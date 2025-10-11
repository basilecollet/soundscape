<?php

declare(strict_types=1);

use App\Application\Admin\Commands\DraftProject\DraftProjectHandler;
use App\Domain\Admin\Entities\Enums\ProjectStatus;
use App\Domain\Admin\Entities\Project;
use App\Domain\Admin\Entities\ValueObjects\ProjectSlug;
use App\Domain\Admin\Entities\ValueObjects\ProjectTitle;
use App\Domain\Admin\Exceptions\ProjectCannotBeDraftedException;
use App\Domain\Admin\Exceptions\ProjectNotFoundException;
use App\Infra\Repositories\Admin\ProjectDatabaseRepository;

test('can set published project back to draft', function () {
    /** @var ProjectDatabaseRepository&\Mockery\MockInterface $repository */
    $repository = Mockery::mock(ProjectDatabaseRepository::class);

    $project = Project::reconstitute(
        title: ProjectTitle::fromString('Published Project'),
        slug: ProjectSlug::fromString('published-project'),
        status: ProjectStatus::Published
    );

    /** @phpstan-ignore method.notFound */
    $repository->shouldReceive('getBySlug')
        ->once()
        ->with(Mockery::on(fn ($arg) => $arg instanceof ProjectSlug && (string) $arg === 'published-project'))
        ->andReturn($project);

    /** @phpstan-ignore method.notFound */
    $repository->shouldReceive('store')
        ->once()
        ->with(Mockery::on(function ($arg) {
            return $arg instanceof Project
                && $arg->getStatus() === ProjectStatus::Draft;
        }))
        ->andReturnNull();

    $handler = new DraftProjectHandler($repository);

    $handler->handle('published-project');

    expect($project->getStatus())->toBe(ProjectStatus::Draft);
});

test('can set archived project back to draft', function () {
    /** @var ProjectDatabaseRepository&\Mockery\MockInterface $repository */
    $repository = Mockery::mock(ProjectDatabaseRepository::class);

    $project = Project::reconstitute(
        title: ProjectTitle::fromString('Archived Project'),
        slug: ProjectSlug::fromString('archived-project'),
        status: ProjectStatus::Archived
    );

    /** @phpstan-ignore method.notFound */
    $repository->shouldReceive('getBySlug')
        ->once()
        ->andReturn($project);

    /** @phpstan-ignore method.notFound */
    $repository->shouldReceive('store')
        ->once()
        ->with(Mockery::on(function ($arg) {
            return $arg instanceof Project
                && $arg->getStatus() === ProjectStatus::Draft;
        }))
        ->andReturnNull();

    $handler = new DraftProjectHandler($repository);

    $handler->handle('archived-project');

    expect($project->getStatus())->toBe(ProjectStatus::Draft);
});

test('cannot set draft project to draft', function () {
    /** @var ProjectDatabaseRepository&\Mockery\MockInterface $repository */
    $repository = Mockery::mock(ProjectDatabaseRepository::class);

    $project = Project::reconstitute(
        title: ProjectTitle::fromString('Draft Project'),
        slug: ProjectSlug::fromString('draft-project'),
        status: ProjectStatus::Draft
    );

    /** @phpstan-ignore method.notFound */
    $repository->shouldReceive('getBySlug')
        ->once()
        ->andReturn($project);

    // store should NOT be called because an exception will be thrown
    $repository->shouldNotReceive('store');

    $handler = new DraftProjectHandler($repository);

    expect(fn () => $handler->handle('draft-project'))
        ->toThrow(ProjectCannotBeDraftedException::class);
});

test('handler throws exception when project not found', function () {
    /** @var ProjectDatabaseRepository&\Mockery\MockInterface $repository */
    $repository = Mockery::mock(ProjectDatabaseRepository::class);

    /** @phpstan-ignore method.notFound */
    $repository->shouldReceive('getBySlug')
        ->once()
        ->andThrow(ProjectNotFoundException::forSlug(ProjectSlug::fromString('non-existent')));

    $handler = new DraftProjectHandler($repository);

    expect(fn () => $handler->handle('non-existent'))
        ->toThrow(ProjectNotFoundException::class);
});

test('handler calls repository methods correctly', function () {
    /** @var ProjectDatabaseRepository&\Mockery\MockInterface $repository */
    $repository = Mockery::mock(ProjectDatabaseRepository::class);

    $project = Project::reconstitute(
        title: ProjectTitle::fromString('Test Project'),
        slug: ProjectSlug::fromString('test-project'),
        status: ProjectStatus::Published
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

    $handler = new DraftProjectHandler($repository);

    $handler->handle('test-project');
});
