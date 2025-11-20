<?php

declare(strict_types=1);

use App\Application\Portfolio\DTOs\PublishedProjectDetailData;
use App\Application\Portfolio\Queries\GetPublishedProjectBySlug\GetPublishedProjectBySlugHandler;
use App\Domain\Portfolio\Entities\Image;
use App\Domain\Portfolio\Entities\PublishedProject;
use App\Domain\Portfolio\Entities\ValueObjects\ProjectDate;
use App\Domain\Portfolio\Entities\ValueObjects\ProjectDescription;
use App\Domain\Portfolio\Entities\ValueObjects\ProjectShortDescription;
use App\Domain\Portfolio\Entities\ValueObjects\ProjectSlug;
use App\Domain\Portfolio\Entities\ValueObjects\ProjectTitle;
use App\Domain\Portfolio\Repositories\ProjectRepository;

test('can get a published project by slug', function () {
    // Arrange
    $project = PublishedProject::reconstitute(
        title: ProjectTitle::fromString('My Project'),
        slug: ProjectSlug::fromString('my-project'),
        description: ProjectDescription::fromString('Complete project description')
    );

    /** @var ProjectRepository&\Mockery\MockInterface $repository */
    $repository = Mockery::mock(ProjectRepository::class);
    /** @phpstan-ignore method.notFound, method.nonObject */
    $repository->shouldReceive('getBySlug')
        ->once()
        ->with('my-project')
        ->andReturn($project);

    $handler = new GetPublishedProjectBySlugHandler($repository);

    // Act
    $result = $handler->handle('my-project');

    // Assert
    expect($result)->toBeInstanceOf(PublishedProjectDetailData::class)
        ->and($result->title)->toBe('My Project')
        ->and($result->slug)->toBe('my-project')
        ->and($result->description)->toBe('Complete project description');
});

test('transforms all project fields correctly including gallery', function () {
    // Arrange
    $featuredImage = new Image(
        webUrl: 'https://example.com/featured.jpg',
        thumbUrl: 'https://example.com/featured-thumb.jpg',
        alt: 'Featured'
    );

    $galleryImage1 = new Image(
        webUrl: 'https://example.com/gallery1.jpg',
        thumbUrl: 'https://example.com/gallery1-thumb.jpg',
        alt: 'Gallery 1'
    );

    $galleryImage2 = new Image(
        webUrl: 'https://example.com/gallery2.jpg',
        thumbUrl: 'https://example.com/gallery2-thumb.jpg',
        alt: 'Gallery 2'
    );

    $project = PublishedProject::reconstitute(
        title: ProjectTitle::fromString('Complete Project'),
        slug: ProjectSlug::fromString('complete-project'),
        description: ProjectDescription::fromString('Full description with **markdown**'),
        shortDescription: ProjectShortDescription::fromString('Short desc'),
        projectDate: ProjectDate::fromString('2024-06-15'),
        featuredImage: $featuredImage,
        galleryImages: [$galleryImage1, $galleryImage2]
    );

    /** @var ProjectRepository&\Mockery\MockInterface $repository */
    $repository = Mockery::mock(ProjectRepository::class);
    /** @phpstan-ignore method.notFound, method.nonObject */
    $repository->shouldReceive('getBySlug')
        ->once()
        ->with('complete-project')
        ->andReturn($project);

    $handler = new GetPublishedProjectBySlugHandler($repository);

    // Act
    $result = $handler->handle('complete-project');

    // Assert
    expect($result->title)->toBe('Complete Project')
        ->and($result->slug)->toBe('complete-project')
        ->and($result->description)->toBe('Full description with **markdown**')
        ->and($result->shortDescription)->toBe('Short desc')
        ->and($result->projectDate)->toBe('2024-06-15')
        ->and($result->featuredImage)->not->toBeNull()
        ->and($result->galleryImages)->toHaveCount(2);
});
