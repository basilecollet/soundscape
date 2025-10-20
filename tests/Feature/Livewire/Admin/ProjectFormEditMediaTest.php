<?php

declare(strict_types=1);

use App\Livewire\Admin\ProjectFormEdit;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('public');
});

test('shows error when featured image is too large', function () {
    $project = Project::factory()->create();
    $largeFile = UploadedFile::fake()->create('large.jpg', 11000); // 11MB

    Livewire::actingAs(User::factory()->create())
        ->test(ProjectFormEdit::class, ['project' => $project])
        ->set('featuredImage', $largeFile)
        ->call('saveFeaturedImage')
        ->assertHasErrors(['featuredImage']);
});

test('shows error when featured image has invalid mime type', function () {
    $project = Project::factory()->create();
    $invalidFile = UploadedFile::fake()->create('document.pdf', 100, 'application/pdf');

    Livewire::actingAs(User::factory()->create())
        ->test(ProjectFormEdit::class, ['project' => $project])
        ->set('featuredImage', $invalidFile)
        ->call('saveFeaturedImage')
        ->assertHasErrors(['featuredImage']);
});

test('shows error when featured image dimensions are too small', function () {
    $project = Project::factory()->create();
    $smallImage = UploadedFile::fake()->image('small.jpg', 500, 400); // < 800x600

    Livewire::actingAs(User::factory()->create())
        ->test(ProjectFormEdit::class, ['project' => $project])
        ->set('featuredImage', $smallImage)
        ->call('saveFeaturedImage')
        ->assertHasErrors(['featuredImage']);
});

test('uploads featured image successfully with conversions', function () {
    $project = Project::factory()->create();
    $file = UploadedFile::fake()->image('featured.jpg', 1920, 1080);

    Livewire::actingAs(User::factory()->create())
        ->test(ProjectFormEdit::class, ['project' => $project])
        ->set('featuredImage', $file)
        ->call('saveFeaturedImage')
        ->assertHasNoErrors()
        ->assertDispatched('featured-image-uploaded');

    $media = $project->fresh()->getFirstMedia('featured');
    expect($media)->not->toBeNull()
        ->and($media->hasGeneratedConversion('thumb'))->toBeTrue()
        ->and($media->hasGeneratedConversion('web'))->toBeTrue()
        ->and($media->hasGeneratedConversion('preview'))->toBeTrue();
});

test('clears featured image property after successful upload', function () {
    $project = Project::factory()->create();
    $file = UploadedFile::fake()->image('featured.jpg', 1920, 1080);

    Livewire::actingAs(User::factory()->create())
        ->test(ProjectFormEdit::class, ['project' => $project])
        ->set('featuredImage', $file)
        ->call('saveFeaturedImage')
        ->assertSet('featuredImage', null);
});

test('shows error when gallery images are too large', function () {
    $project = Project::factory()->create();
    $largeFile = UploadedFile::fake()->create('large.jpg', 11000); // 11MB

    Livewire::actingAs(User::factory()->create())
        ->test(ProjectFormEdit::class, ['project' => $project])
        ->set('galleryImages', [$largeFile])
        ->call('saveGalleryImages')
        ->assertHasErrors(['galleryImages.*']);
});

test('shows error when gallery images have invalid mime type', function () {
    $project = Project::factory()->create();
    $invalidFile = UploadedFile::fake()->create('document.txt', 100, 'text/plain');

    Livewire::actingAs(User::factory()->create())
        ->test(ProjectFormEdit::class, ['project' => $project])
        ->set('galleryImages', [$invalidFile])
        ->call('saveGalleryImages')
        ->assertHasErrors(['galleryImages.*']);
});

test('shows error when gallery images dimensions are too small', function () {
    $project = Project::factory()->create();
    $smallImage = UploadedFile::fake()->image('small.jpg', 500, 400);

    Livewire::actingAs(User::factory()->create())
        ->test(ProjectFormEdit::class, ['project' => $project])
        ->set('galleryImages', [$smallImage])
        ->call('saveGalleryImages')
        ->assertHasErrors(['galleryImages.*']);
});

test('uploads gallery images successfully with conversions', function () {
    $project = Project::factory()->create();
    $images = [
        UploadedFile::fake()->image('gallery1.jpg', 1920, 1080),
        UploadedFile::fake()->image('gallery2.jpg', 1920, 1080),
    ];

    Livewire::actingAs(User::factory()->create())
        ->test(ProjectFormEdit::class, ['project' => $project])
        ->set('galleryImages', $images)
        ->call('saveGalleryImages')
        ->assertHasNoErrors()
        ->assertDispatched('gallery-images-uploaded');

    $galleryMedia = $project->fresh()->getMedia('gallery');
    expect($galleryMedia)->toHaveCount(2);

    foreach ($galleryMedia as $media) {
        expect($media->hasGeneratedConversion('thumb'))->toBeTrue()
            ->and($media->hasGeneratedConversion('web'))->toBeTrue()
            ->and($media->hasGeneratedConversion('preview'))->toBeTrue();
    }
});

test('clears gallery images property after successful upload', function () {
    $project = Project::factory()->create();
    $images = [
        UploadedFile::fake()->image('gallery1.jpg', 1920, 1080),
        UploadedFile::fake()->image('gallery2.jpg', 1920, 1080),
    ];

    Livewire::actingAs(User::factory()->create())
        ->test(ProjectFormEdit::class, ['project' => $project])
        ->set('galleryImages', $images)
        ->call('saveGalleryImages')
        ->assertSet('galleryImages', []);
});

test('validates featured image in real-time on update', function () {
    $project = Project::factory()->create();
    $largeFile = UploadedFile::fake()->create('large.jpg', 11000);

    Livewire::actingAs(User::factory()->create())
        ->test(ProjectFormEdit::class, ['project' => $project])
        ->set('featuredImage', $largeFile)
        ->assertHasErrors(['featuredImage']);
});

test('validates gallery images in real-time on update', function () {
    $project = Project::factory()->create();
    $largeFile = UploadedFile::fake()->create('large.jpg', 11000);

    Livewire::actingAs(User::factory()->create())
        ->test(ProjectFormEdit::class, ['project' => $project])
        ->set('galleryImages', [$largeFile])
        ->assertHasErrors(['galleryImages.*']);
});

test('rejects more than 10 gallery images at once', function () {
    $project = Project::factory()->create();
    $images = [];
    for ($i = 0; $i < 11; $i++) {
        $images[] = UploadedFile::fake()->image("test{$i}.jpg", 1000, 800);
    }

    Livewire::actingAs(User::factory()->create())
        ->test(ProjectFormEdit::class, ['project' => $project])
        ->set('galleryImages', $images)
        ->call('saveGalleryImages')
        ->assertHasErrors(['galleryImages']);
});

test('handles file upload errors gracefully', function () {
    $project = Project::factory()->create();

    // Simuler une erreur en passant null
    Livewire::actingAs(User::factory()->create())
        ->test(ProjectFormEdit::class, ['project' => $project])
        ->set('featuredImage', null)
        ->call('saveFeaturedImage')
        ->assertHasNoErrors(); // Ne devrait rien faire si null
});
