<?php

declare(strict_types=1);

use App\Domain\Portfolio\Entities\Image;
use App\Domain\Portfolio\Entities\PublishedProject;
use App\Domain\Portfolio\Entities\ValueObjects\ProjectDate;
use App\Domain\Portfolio\Entities\ValueObjects\ProjectDescription;
use App\Domain\Portfolio\Entities\ValueObjects\ProjectShortDescription;
use App\Domain\Portfolio\Entities\ValueObjects\ProjectSlug;
use App\Domain\Portfolio\Entities\ValueObjects\ProjectTitle;

test('can create a published project with required data', function () {
    $description = ProjectDescription::fromString('A complete project description');

    $project = PublishedProject::reconstitute(
        title: ProjectTitle::fromString('My Project'),
        slug: ProjectSlug::fromString('my-project'),
        description: $description
    );

    expect($project)->toBeInstanceOf(PublishedProject::class)
        ->and($project->getTitle())->toBeInstanceOf(ProjectTitle::class)
        ->and((string) $project->getTitle())->toBe('My Project')
        ->and($project->getSlug())->toBeInstanceOf(ProjectSlug::class)
        ->and((string) $project->getSlug())->toBe('my-project')
        ->and($project->getDescription())->toBeInstanceOf(ProjectDescription::class)
        ->and((string) $project->getDescription())->toBe('A complete project description')
        ->and($project->getShortDescription())->toBeNull()
        ->and($project->getProjectDate())->toBeNull()
        ->and($project->getFeaturedImage())->toBeNull()
        ->and($project->getGalleryImages())->toBeEmpty();
});

test('can create a published project with all data', function () {
    $description = ProjectDescription::fromString('Complete project description');
    $featuredImage = new Image(
        webUrl: 'https://example.com/image-web.jpg',
        thumbUrl: 'https://example.com/image-thumb.jpg',
        alt: 'Featured image'
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
        description: $description,
        shortDescription: ProjectShortDescription::fromString('A short description'),
        projectDate: ProjectDate::fromString('2024-06-15'),
        featuredImage: $featuredImage,
        galleryImages: [$galleryImage1, $galleryImage2]
    );

    expect($project->getDescription())->toBeInstanceOf(ProjectDescription::class)
        ->and((string) $project->getDescription())->toBe('Complete project description')
        ->and($project->getShortDescription())->toBeInstanceOf(ProjectShortDescription::class)
        ->and((string) $project->getShortDescription())->toBe('A short description')
        ->and($project->getProjectDate())->toBeInstanceOf(ProjectDate::class);

    $projectDate = $project->getProjectDate();
    assert($projectDate !== null); // Type narrowing for PHPStan
    expect($projectDate->format('Y-m-d'))->toBe('2024-06-15')
        ->and($project->getFeaturedImage())->toBe($featuredImage)
        ->and($project->getGalleryImages())->toHaveCount(2)
        ->and($project->getGalleryImages()[0])->toBe($galleryImage1)
        ->and($project->getGalleryImages()[1])->toBe($galleryImage2);
});

test('published project can convert to array', function () {
    $description = ProjectDescription::fromString('Project description');

    $project = PublishedProject::reconstitute(
        title: ProjectTitle::fromString('My Project'),
        slug: ProjectSlug::fromString('my-project'),
        description: $description
    );

    $array = $project->toArray();

    expect($array)->toBeArray()
        ->and($array)->toHaveKey('title')
        ->and($array)->toHaveKey('slug')
        ->and($array)->toHaveKey('description')
        ->and($array['title'])->toBe('My Project')
        ->and($array['slug'])->toBe('my-project')
        ->and($array['description'])->toBe('Project description')
        ->and($array['short_description'])->toBeNull()
        ->and($array['project_date'])->toBeNull();
});

test('published project toArray includes all fields when set', function () {
    $description = ProjectDescription::fromString('Full description');

    $project = PublishedProject::reconstitute(
        title: ProjectTitle::fromString('Complete Project'),
        slug: ProjectSlug::fromString('complete-project'),
        description: $description,
        shortDescription: ProjectShortDescription::fromString('Short desc'),
        projectDate: ProjectDate::fromString('2024-06-15')
    );

    $array = $project->toArray();

    expect($array['description'])->toBe('Full description')
        ->and($array['short_description'])->toBe('Short desc')
        ->and($array['project_date'])->toBe('2024-06-15');
});

test('published project is readonly', function () {
    $description = ProjectDescription::fromString('Project description');

    $project = PublishedProject::reconstitute(
        title: ProjectTitle::fromString('My Project'),
        slug: ProjectSlug::fromString('my-project'),
        description: $description
    );

    $reflection = new ReflectionClass($project);
    expect($reflection->isReadOnly())->toBeTrue();
});

test('can create published project with description and markdown', function () {
    $markdown = "# Project Overview\n\n- Feature 1\n- Feature 2\n\n**Bold text**";
    $description = ProjectDescription::fromString($markdown);

    $project = PublishedProject::reconstitute(
        title: ProjectTitle::fromString('My Project'),
        slug: ProjectSlug::fromString('my-project'),
        description: $description
    );

    expect((string) $project->getDescription())->toBe($markdown);
});

test('gallery images can be empty in published project', function () {
    $description = ProjectDescription::fromString('Project description');

    $project = PublishedProject::reconstitute(
        title: ProjectTitle::fromString('My Project'),
        slug: ProjectSlug::fromString('my-project'),
        description: $description
    );

    expect($project->getGalleryImages())->toBeArray()
        ->and($project->getGalleryImages())->toBeEmpty();
});
