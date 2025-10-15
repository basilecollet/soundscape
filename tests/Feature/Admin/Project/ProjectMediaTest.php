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

test('can add featured image to project', function () {
    $project = Project::factory()->create();
    $file = UploadedFile::fake()->image('featured.jpg', 1920, 1080);

    $media = $project->addMedia($file)
        ->toMediaCollection('featured');

    expect($media)->not->toBeNull()
        ->and($project->getFirstMedia('featured'))->not->toBeNull()
        ->and($project->getFirstMedia('featured')->file_name)->toBe('featured.jpg');
});

test('replacing featured image removes previous one', function () {
    $project = Project::factory()->create();

    $firstImage = UploadedFile::fake()->image('first.jpg');
    $project->addMedia($firstImage)->toMediaCollection('featured');

    $secondImage = UploadedFile::fake()->image('second.jpg');
    $project->addMedia($secondImage)->toMediaCollection('featured');

    expect($project->getMedia('featured')->count())->toBe(1)
        ->and($project->getFirstMedia('featured')->file_name)->toBe('second.jpg');
});

test('can add multiple images to gallery', function () {
    $project = Project::factory()->create();

    $image1 = UploadedFile::fake()->image('gallery1.jpg');
    $image2 = UploadedFile::fake()->image('gallery2.jpg');
    $image3 = UploadedFile::fake()->image('gallery3.jpg');

    $project->addMedia($image1)->toMediaCollection('gallery');
    $project->addMedia($image2)->toMediaCollection('gallery');
    $project->addMedia($image3)->toMediaCollection('gallery');

    expect($project->getMedia('gallery')->count())->toBe(3)
        ->and($project->getMedia('gallery')->pluck('file_name')->toArray())
        ->toEqual(['gallery1.jpg', 'gallery2.jpg', 'gallery3.jpg']);
});

test('featured and gallery collections are independent', function () {
    $project = Project::factory()->create();

    $featuredImage = UploadedFile::fake()->image('featured.jpg');
    $galleryImage1 = UploadedFile::fake()->image('gallery1.jpg');
    $galleryImage2 = UploadedFile::fake()->image('gallery2.jpg');

    $project->addMedia($featuredImage)->toMediaCollection('featured');
    $project->addMedia($galleryImage1)->toMediaCollection('gallery');
    $project->addMedia($galleryImage2)->toMediaCollection('gallery');

    expect($project->getFirstMedia('featured'))->not->toBeNull()
        ->and($project->getFirstMedia('featured')->file_name)->toBe('featured.jpg')
        ->and($project->getMedia('gallery')->count())->toBe(2);
});

test('can retrieve featured image url', function () {
    $project = Project::factory()->create();
    $file = UploadedFile::fake()->image('featured.jpg');

    $project->addMedia($file)->toMediaCollection('featured');

    $url = $project->getFirstMediaUrl('featured');

    expect($url)->not->toBeEmpty()
        ->and($url)->toContain('featured.jpg');
});

test('returns empty string when no featured image', function () {
    $project = Project::factory()->create();

    $url = $project->getFirstMediaUrl('featured');

    expect($url)->toBe('');
});

test('can delete featured image', function () {
    $project = Project::factory()->create();
    $file = UploadedFile::fake()->image('featured.jpg');

    $media = $project->addMedia($file)->toMediaCollection('featured');
    expect($project->getFirstMedia('featured'))->not->toBeNull();

    $media->delete();

    expect($project->fresh()->getFirstMedia('featured'))->toBeNull();
});

test('can delete specific gallery image', function () {
    $project = Project::factory()->create();

    $image1 = UploadedFile::fake()->image('gallery1.jpg');
    $image2 = UploadedFile::fake()->image('gallery2.jpg');
    $image3 = UploadedFile::fake()->image('gallery3.jpg');

    $project->addMedia($image1)->toMediaCollection('gallery');
    $media2 = $project->addMedia($image2)->toMediaCollection('gallery');
    $project->addMedia($image3)->toMediaCollection('gallery');

    expect($project->getMedia('gallery')->count())->toBe(3);

    $media2->delete();

    expect($project->fresh()->getMedia('gallery')->count())->toBe(2)
        ->and($project->fresh()->getMedia('gallery')->pluck('file_name')->toArray())
        ->toEqual(['gallery1.jpg', 'gallery3.jpg']);
});

test('only accepts image mime types for featured collection', function () {
    $project = Project::factory()->create();
    $file = UploadedFile::fake()->create('document.pdf', 100, 'application/pdf');

    $project->addMedia($file)->toMediaCollection('featured');
})->throws(\Spatie\MediaLibrary\MediaCollections\Exceptions\FileCannotBeAdded::class);

test('only accepts image mime types for gallery collection', function () {
    $project = Project::factory()->create();
    $file = UploadedFile::fake()->create('document.txt', 100, 'text/plain');

    $project->addMedia($file)->toMediaCollection('gallery');
})->throws(\Spatie\MediaLibrary\MediaCollections\Exceptions\FileCannotBeAdded::class);
