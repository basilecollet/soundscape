<?php

declare(strict_types=1);

use App\Application\Admin\DTOs\ProjectData;
use App\Domain\Admin\Entities\Project;

test('can create ProjectData from entity with minimal fields', function () {
    $project = Project::new('My Project');

    $data = ProjectData::fromEntity($project);

    expect($data)->toBeInstanceOf(ProjectData::class)
        ->and($data->title)->toBe('My Project')
        ->and($data->slug)->toBe('my-project')
        ->and($data->status)->toBe('draft')
        ->and($data->description)->toBeNull()
        ->and($data->shortDescription)->toBeNull()
        ->and($data->clientName)->toBeNull()
        ->and($data->projectDate)->toBeNull();
});

test('can create ProjectData from entity with all fields', function () {
    $project = Project::new(
        'My Project',
        'Long description',
        'Short desc',
        'acme corporation',
        '2024-06-15'
    );

    $project->publish();

    $data = ProjectData::fromEntity($project);

    expect($data->title)->toBe('My Project')
        ->and($data->slug)->toBe('my-project')
        ->and($data->status)->toBe('published')
        ->and($data->description)->toBe('Long description')
        ->and($data->shortDescription)->toBe('Short desc')
        ->and($data->clientName)->toBe('Acme Corporation')
        ->and($data->projectDate)->toBe('2024-06-15');
});

test('ProjectData formats client name correctly', function () {
    $project = Project::new('My Project', null, null, 'john doe company');

    $data = ProjectData::fromEntity($project);

    expect($data->clientName)->toBe('John Doe Company');
});

test('can convert ProjectData to array', function () {
    $project = Project::new(
        'My Project',
        'Description',
        'Short',
        'Client Inc',
        '2024-06-15'
    );

    $data = ProjectData::fromEntity($project);
    $array = $data->toArray();

    expect($array)->toBeArray()
        ->and($array)->toHaveKey('title')
        ->and($array)->toHaveKey('slug')
        ->and($array)->toHaveKey('status')
        ->and($array)->toHaveKey('description')
        ->and($array)->toHaveKey('short_description')
        ->and($array)->toHaveKey('client_name')
        ->and($array)->toHaveKey('project_date')
        ->and($array['title'])->toBe('My Project')
        ->and($array['slug'])->toBe('my-project')
        ->and($array['status'])->toBe('draft');
});

test('toArray includes null values for optional fields', function () {
    $project = Project::new('My Project');

    $data = ProjectData::fromEntity($project);
    $array = $data->toArray();

    expect($array['description'])->toBeNull()
        ->and($array['short_description'])->toBeNull()
        ->and($array['client_name'])->toBeNull()
        ->and($array['project_date'])->toBeNull();
});

// ========== Image Tests ==========

test('ProjectData has null featured image by default', function () {
    $project = Project::new('My Project');

    $data = ProjectData::fromEntity($project);

    expect($data->featuredImage)->toBeNull();
});

test('ProjectData has empty gallery images by default', function () {
    $project = Project::new('My Project');

    $data = ProjectData::fromEntity($project);

    expect($data->galleryImages)->toBeArray()
        ->and($data->galleryImages)->toBeEmpty();
});

test('ProjectData can have featured image', function () {
    $featuredImage = new \App\Domain\Admin\Entities\Image(
        originalUrl: 'https://example.com/featured.jpg',
        thumbUrl: 'https://example.com/featured-thumb.jpg',
        webUrl: 'https://example.com/featured-web.jpg',
        previewUrl: 'https://example.com/featured-preview.jpg',
        alt: 'Featured'
    );

    $project = \App\Domain\Admin\Entities\Project::reconstitute(
        title: \App\Domain\Admin\Entities\ValueObjects\ProjectTitle::fromString('My Project'),
        slug: \App\Domain\Admin\Entities\ValueObjects\ProjectSlug::fromString('my-project'),
        status: \App\Domain\Admin\Entities\Enums\ProjectStatus::Draft,
        featuredImage: $featuredImage
    );

    $data = ProjectData::fromEntity($project);

    expect($data->featuredImage)->not->toBeNull()
        ->and($data->featuredImage)->toBeInstanceOf(\App\Application\Admin\DTOs\ImageData::class);

    assert($data->featuredImage !== null); // Type narrowing for PHPStan
    expect($data->featuredImage->thumbUrl)->toBe('https://example.com/featured-thumb.jpg');
});

test('ProjectData can have gallery images', function () {
    $image1 = new \App\Domain\Admin\Entities\Image(
        originalUrl: 'https://example.com/gallery1.jpg',
        thumbUrl: 'https://example.com/gallery1-thumb.jpg',
        webUrl: 'https://example.com/gallery1-web.jpg',
        previewUrl: 'https://example.com/gallery1-preview.jpg'
    );

    $image2 = new \App\Domain\Admin\Entities\Image(
        originalUrl: 'https://example.com/gallery2.jpg',
        thumbUrl: 'https://example.com/gallery2-thumb.jpg',
        webUrl: 'https://example.com/gallery2-web.jpg',
        previewUrl: 'https://example.com/gallery2-preview.jpg'
    );

    $project = \App\Domain\Admin\Entities\Project::reconstitute(
        title: \App\Domain\Admin\Entities\ValueObjects\ProjectTitle::fromString('My Project'),
        slug: \App\Domain\Admin\Entities\ValueObjects\ProjectSlug::fromString('my-project'),
        status: \App\Domain\Admin\Entities\Enums\ProjectStatus::Draft,
        galleryImages: [$image1, $image2]
    );

    $data = ProjectData::fromEntity($project);

    expect($data->galleryImages)->toHaveCount(2)
        ->and($data->galleryImages[0])->toBeInstanceOf(\App\Application\Admin\DTOs\ImageData::class)
        ->and($data->galleryImages[0]->thumbUrl)->toBe('https://example.com/gallery1-thumb.jpg')
        ->and($data->galleryImages[1]->thumbUrl)->toBe('https://example.com/gallery2-thumb.jpg');
});
