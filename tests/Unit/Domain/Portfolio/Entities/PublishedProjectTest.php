<?php

declare(strict_types=1);

use App\Domain\Portfolio\Entities\Image;
use App\Domain\Portfolio\Entities\PublishedProject;
use App\Domain\Portfolio\Entities\ValueObjects\ProjectDate;
use App\Domain\Portfolio\Entities\ValueObjects\ProjectShortDescription;
use App\Domain\Portfolio\Entities\ValueObjects\ProjectSlug;
use App\Domain\Portfolio\Entities\ValueObjects\ProjectTitle;

test('can create a published project with minimal data', function () {
    $project = PublishedProject::reconstitute(
        title: ProjectTitle::fromString('My Project'),
        slug: ProjectSlug::fromString('my-project')
    );

    expect($project)->toBeInstanceOf(PublishedProject::class)
        ->and($project->getTitle())->toBeInstanceOf(ProjectTitle::class)
        ->and((string) $project->getTitle())->toBe('My Project')
        ->and($project->getSlug())->toBeInstanceOf(ProjectSlug::class)
        ->and((string) $project->getSlug())->toBe('my-project')
        ->and($project->getShortDescription())->toBeNull()
        ->and($project->getProjectDate())->toBeNull()
        ->and($project->getFeaturedImage())->toBeNull();
});

test('can create a published project with all data', function () {
    $featuredImage = new Image(
        webUrl: 'https://example.com/image-web.jpg',
        thumbUrl: 'https://example.com/image-thumb.jpg',
        alt: 'Featured image'
    );

    $project = PublishedProject::reconstitute(
        title: ProjectTitle::fromString('Complete Project'),
        slug: ProjectSlug::fromString('complete-project'),
        shortDescription: ProjectShortDescription::fromString('A short description'),
        projectDate: ProjectDate::fromString('2024-06-15'),
        featuredImage: $featuredImage
    );

    expect($project->getShortDescription())->toBeInstanceOf(ProjectShortDescription::class)
        ->and((string) $project->getShortDescription())->toBe('A short description')
        ->and($project->getProjectDate())->toBeInstanceOf(ProjectDate::class);

    $projectDate = $project->getProjectDate();
    assert($projectDate !== null); // Type narrowing for PHPStan
    expect($projectDate->format('Y-m-d'))->toBe('2024-06-15')
        ->and($project->getFeaturedImage())->toBe($featuredImage);
});

test('published project can convert to array', function () {
    $project = PublishedProject::reconstitute(
        title: ProjectTitle::fromString('My Project'),
        slug: ProjectSlug::fromString('my-project')
    );

    $array = $project->toArray();

    expect($array)->toBeArray()
        ->and($array)->toHaveKey('title')
        ->and($array)->toHaveKey('slug')
        ->and($array['title'])->toBe('My Project')
        ->and($array['slug'])->toBe('my-project')
        ->and($array['short_description'])->toBeNull()
        ->and($array['project_date'])->toBeNull();
});

test('published project toArray includes all fields when set', function () {
    $project = PublishedProject::reconstitute(
        title: ProjectTitle::fromString('Complete Project'),
        slug: ProjectSlug::fromString('complete-project'),
        shortDescription: ProjectShortDescription::fromString('Short desc'),
        projectDate: ProjectDate::fromString('2024-06-15')
    );

    $array = $project->toArray();

    expect($array['short_description'])->toBe('Short desc')
        ->and($array['project_date'])->toBe('2024-06-15');
});

test('published project is readonly', function () {
    $project = PublishedProject::reconstitute(
        title: ProjectTitle::fromString('My Project'),
        slug: ProjectSlug::fromString('my-project')
    );

    $reflection = new ReflectionClass($project);
    expect($reflection->isReadOnly())->toBeTrue();
});
