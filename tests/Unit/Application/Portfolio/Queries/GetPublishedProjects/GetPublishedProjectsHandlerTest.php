<?php

declare(strict_types=1);

use App\Application\Portfolio\DTOs\PublishedProjectData;
use App\Application\Portfolio\Queries\GetPublishedProjects\GetPublishedProjectsHandler;
use App\Domain\Portfolio\Entities\PublishedProject;
use App\Domain\Portfolio\Entities\ValueObjects\ProjectSlug;
use App\Domain\Portfolio\Entities\ValueObjects\ProjectTitle;
use App\Domain\Portfolio\Repositories\ProjectRepository;

test('can get all published projects', function () {
    // Arrange
    $project1 = PublishedProject::reconstitute(
        title: ProjectTitle::fromString('Project 1'),
        slug: ProjectSlug::fromString('project-1')
    );
    $project2 = PublishedProject::reconstitute(
        title: ProjectTitle::fromString('Project 2'),
        slug: ProjectSlug::fromString('project-2')
    );

    /** @var ProjectRepository&\Mockery\MockInterface $repository */
    $repository = Mockery::mock(ProjectRepository::class);
    /** @phpstan-ignore method.notFound, method.nonObject */
    $repository->shouldReceive('getAll')
        ->once()
        ->andReturn(collect([$project1, $project2]));

    $handler = new GetPublishedProjectsHandler($repository);

    // Act
    $result = $handler->handle();

    // Assert
    expect($result)->toHaveCount(2)
        ->and($result[0])->toBeInstanceOf(PublishedProjectData::class)
        ->and($result[1])->toBeInstanceOf(PublishedProjectData::class);
});

test('returns empty collection when no published projects', function () {
    // Arrange
    /** @var ProjectRepository&\Mockery\MockInterface $repository */
    $repository = Mockery::mock(ProjectRepository::class);
    /** @phpstan-ignore method.notFound, method.nonObject */
    $repository->shouldReceive('getAll')
        ->once()
        ->andReturn(collect([]));

    $handler = new GetPublishedProjectsHandler($repository);

    // Act
    $result = $handler->handle();

    // Assert
    expect($result)->toBeEmpty();
});

test('transforms all project fields correctly', function () {
    // Arrange
    $project = PublishedProject::reconstitute(
        title: ProjectTitle::fromString('Complete Project'),
        slug: ProjectSlug::fromString('complete-project')
    );

    /** @var ProjectRepository&\Mockery\MockInterface $repository */
    $repository = Mockery::mock(ProjectRepository::class);
    /** @phpstan-ignore method.notFound, method.nonObject */
    $repository->shouldReceive('getAll')
        ->once()
        ->andReturn(collect([$project]));

    $handler = new GetPublishedProjectsHandler($repository);

    // Act
    $result = $handler->handle();

    // Assert
    $projectData = $result->first();
    assert($projectData instanceof PublishedProjectData);
    expect($projectData->title)->toBe('Complete Project')
        ->and($projectData->slug)->toBe('complete-project');
});
