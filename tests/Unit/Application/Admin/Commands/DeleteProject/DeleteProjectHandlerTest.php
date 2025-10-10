<?php

declare(strict_types=1);

use App\Application\Admin\Commands\DeleteProject\DeleteProjectHandler;
use App\Domain\Admin\Entities\ValueObjects\ProjectSlug;
use App\Domain\Admin\Exceptions\ProjectNotFoundException;
use App\Infra\Repositories\Admin\ProjectDatabaseRepository;

test('can delete project by slug', function () {
    /** @var ProjectDatabaseRepository&\Mockery\MockInterface $repository */
    $repository = Mockery::mock(ProjectDatabaseRepository::class);
    /** @phpstan-ignore method.notFound */
    $repository->shouldReceive('delete')
        ->once()
        ->with(Mockery::on(fn ($arg) => $arg instanceof ProjectSlug && (string) $arg === 'my-project'))
        ->andReturnNull();

    $handler = new DeleteProjectHandler($repository);

    $handler->handle('my-project');

    expect(true)->toBeTrue(); // If no exception, test passes
});

test('handler throws exception when project not found', function () {
    /** @var ProjectDatabaseRepository&\Mockery\MockInterface $repository */
    $repository = Mockery::mock(ProjectDatabaseRepository::class);
    /** @phpstan-ignore method.notFound */
    $repository->shouldReceive('delete')
        ->once()
        ->andThrow(ProjectNotFoundException::forSlug(ProjectSlug::fromString('non-existent')));

    $handler = new DeleteProjectHandler($repository);

    expect(fn () => $handler->handle('non-existent'))
        ->toThrow(ProjectNotFoundException::class);
});

test('handler calls repository delete method', function () {
    /** @var ProjectDatabaseRepository&\Mockery\MockInterface $repository */
    $repository = Mockery::mock(ProjectDatabaseRepository::class);
    /** @phpstan-ignore method.notFound */
    $repository->shouldReceive('delete')
        ->once()
        ->andReturnNull();

    $handler = new DeleteProjectHandler($repository);

    $handler->handle('test-project');
});
