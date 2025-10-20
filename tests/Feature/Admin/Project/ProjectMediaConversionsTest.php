<?php

declare(strict_types=1);

use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('public');
});

test('generates thumbnail conversion when adding featured image', function () {
    $project = Project::factory()->create();
    $file = UploadedFile::fake()->image('featured.jpg', 1920, 1080);

    $media = $project->addMedia($file)->toMediaCollection('featured');

    expect($media->hasGeneratedConversion('thumb'))->toBeTrue()
        ->and($media->getPath('thumb'))->toBeFile();
});

test('generates web-optimized conversion for featured image', function () {
    $project = Project::factory()->create();
    $file = UploadedFile::fake()->image('test.jpg', 2000, 1500);

    $media = $project->addMedia($file)->toMediaCollection('featured');

    expect($media->hasGeneratedConversion('web'))->toBeTrue();
});

test('generates preview conversion for featured image', function () {
    $project = Project::factory()->create();
    $file = UploadedFile::fake()->image('test.jpg', 1920, 1080);

    $media = $project->addMedia($file)->toMediaCollection('featured');

    expect($media->hasGeneratedConversion('preview'))->toBeTrue();
});

test('generates responsive images for featured image', function () {
    $project = Project::factory()->create();
    $file = UploadedFile::fake()->image('test.jpg', 2000, 1500);

    $media = $project->addMedia($file)->toMediaCollection('featured');

    $media->refresh();
    expect($media->responsive_images)->not->toBeEmpty();
});

test('conversions are generated for gallery images', function () {
    $project = Project::factory()->create();
    $file = UploadedFile::fake()->image('gallery.jpg', 2000, 1500);

    $media = $project->addMedia($file)->toMediaCollection('gallery');

    expect($media->hasGeneratedConversion('thumb'))->toBeTrue()
        ->and($media->hasGeneratedConversion('web'))->toBeTrue()
        ->and($media->hasGeneratedConversion('preview'))->toBeTrue();
});

test('conversions are generated for both featured and gallery collections', function () {
    $project = Project::factory()->create();

    $featured = UploadedFile::fake()->image('featured.jpg', 1920, 1080);
    $gallery = UploadedFile::fake()->image('gallery.jpg', 1920, 1080);

    $featuredMedia = $project->addMedia($featured)->toMediaCollection('featured');
    $galleryMedia = $project->addMedia($gallery)->toMediaCollection('gallery');

    expect($featuredMedia->hasGeneratedConversion('thumb'))->toBeTrue()
        ->and($galleryMedia->hasGeneratedConversion('thumb'))->toBeTrue();
});

test('thumbnail conversion has correct dimensions', function () {
    $project = Project::factory()->create();
    $file = UploadedFile::fake()->image('test.jpg', 2000, 1500);

    $media = $project->addMedia($file)->toMediaCollection('featured');

    $thumbPath = $media->getPath('thumb');
    expect($thumbPath)->toBeFile();

    // Vérifier que les dimensions sont réduites
    $imageSize = getimagesize($thumbPath);
    expect($imageSize[0])->toBeLessThanOrEqual(400) // width
        ->and($imageSize[1])->toBeLessThanOrEqual(300); // height
});

test('web conversion has correct dimensions', function () {
    $project = Project::factory()->create();
    $file = UploadedFile::fake()->image('test.jpg', 2000, 1500);

    $media = $project->addMedia($file)->toMediaCollection('featured');

    $webPath = $media->getPath('web');
    expect($webPath)->toBeFile();

    $imageSize = getimagesize($webPath);
    expect($imageSize[0])->toBeLessThanOrEqual(1200) // width
        ->and($imageSize[1])->toBeLessThanOrEqual(900); // height
});

test('conversions reduce file size compared to original', function () {
    $project = Project::factory()->create();
    $file = UploadedFile::fake()->image('large.jpg', 3000, 2000);

    $media = $project->addMedia($file)->toMediaCollection('featured');

    $originalSize = filesize($media->getPath());
    $thumbSize = filesize($media->getPath('thumb'));
    $webSize = filesize($media->getPath('web'));

    expect($thumbSize)->toBeLessThan($originalSize)
        ->and($webSize)->toBeLessThan($originalSize);
});

test('can retrieve conversion urls', function () {
    $project = Project::factory()->create();
    $file = UploadedFile::fake()->image('test.jpg', 1920, 1080);

    $media = $project->addMedia($file)->toMediaCollection('featured');

    $thumbUrl = $media->getUrl('thumb');
    $webUrl = $media->getUrl('web');
    $previewUrl = $media->getUrl('preview');

    expect($thumbUrl)->not->toBeEmpty()
        ->and($webUrl)->not->toBeEmpty()
        ->and($previewUrl)->not->toBeEmpty();
});
