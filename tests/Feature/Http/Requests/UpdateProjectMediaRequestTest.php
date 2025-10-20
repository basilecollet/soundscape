<?php

declare(strict_types=1);

use App\Http\Requests\Admin\UpdateProjectMediaRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('public');
});

test('validates featured image is an image file', function () {
    $request = new UpdateProjectMediaRequest();

    expect($request->rules()['featuredImage'])->toContain('image');
});

test('validates featured image mime types', function () {
    $request = new UpdateProjectMediaRequest();

    expect($request->rules()['featuredImage'])->toContain('mimes:jpeg,png,gif,webp');
});

test('validates featured image max size is 10MB', function () {
    $request = new UpdateProjectMediaRequest();

    expect($request->rules()['featuredImage'])->toContain('max:10240');
});

test('validates featured image minimum dimensions', function () {
    $request = new UpdateProjectMediaRequest();

    expect($request->rules()['featuredImage'])
        ->toContain('dimensions:min_width=800,min_height=600');
});

test('validates gallery images is an array', function () {
    $request = new UpdateProjectMediaRequest();

    expect($request->rules()['galleryImages'])->toContain('array');
});

test('validates gallery images max count is 10', function () {
    $request = new UpdateProjectMediaRequest();

    expect($request->rules()['galleryImages'])->toContain('max:10');
});

test('validates each gallery image is required when array is provided', function () {
    $request = new UpdateProjectMediaRequest();

    expect($request->rules()['galleryImages.*'])->toContain('required');
});

test('validates each gallery image is an image file', function () {
    $request = new UpdateProjectMediaRequest();

    expect($request->rules()['galleryImages.*'])->toContain('image');
});

test('validates each gallery image mime types', function () {
    $request = new UpdateProjectMediaRequest();

    expect($request->rules()['galleryImages.*'])->toContain('mimes:jpeg,png,gif,webp');
});

test('validates each gallery image max size', function () {
    $request = new UpdateProjectMediaRequest();

    expect($request->rules()['galleryImages.*'])->toContain('max:10240');
});

test('validates each gallery image minimum dimensions', function () {
    $request = new UpdateProjectMediaRequest();

    expect($request->rules()['galleryImages.*'])
        ->toContain('dimensions:min_width=800,min_height=600');
});

test('accepts valid featured image', function () {
    $request = new UpdateProjectMediaRequest();
    $validator = Validator::make(
        ['featuredImage' => UploadedFile::fake()->image('test.jpg', 1000, 800)],
        $request->rules()
    );

    expect($validator->passes())->toBeTrue();
});

test('rejects featured image that is too small', function () {
    $request = new UpdateProjectMediaRequest();
    $validator = Validator::make(
        ['featuredImage' => UploadedFile::fake()->image('test.jpg', 500, 400)],
        $request->rules()
    );

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('featuredImage'))->toBeTrue();
});

test('rejects featured image with invalid mime type', function () {
    $request = new UpdateProjectMediaRequest();
    $validator = Validator::make(
        ['featuredImage' => UploadedFile::fake()->create('document.pdf', 1000, 'application/pdf')],
        $request->rules()
    );

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('featuredImage'))->toBeTrue();
});

test('rejects featured image that is too large', function () {
    $request = new UpdateProjectMediaRequest();
    $validator = Validator::make(
        ['featuredImage' => UploadedFile::fake()->create('large.jpg', 11000)], // 11MB
        $request->rules()
    );

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('featuredImage'))->toBeTrue();
});

test('accepts valid gallery images array', function () {
    $request = new UpdateProjectMediaRequest();
    $validator = Validator::make(
        [
            'galleryImages' => [
                UploadedFile::fake()->image('test1.jpg', 1000, 800),
                UploadedFile::fake()->image('test2.jpg', 1000, 800),
            ],
        ],
        $request->rules()
    );

    expect($validator->passes())->toBeTrue();
});

test('rejects more than 10 gallery images', function () {
    $request = new UpdateProjectMediaRequest();
    $images = [];
    for ($i = 0; $i < 11; $i++) {
        $images[] = UploadedFile::fake()->image("test{$i}.jpg", 1000, 800);
    }

    $validator = Validator::make(
        ['galleryImages' => $images],
        $request->rules()
    );

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('galleryImages'))->toBeTrue();
});

test('has custom error message for featured image dimensions', function () {
    $request = new UpdateProjectMediaRequest();

    expect($request->messages())->toHaveKey('featuredImage.dimensions');
});

test('has custom error message for gallery images dimensions', function () {
    $request = new UpdateProjectMediaRequest();

    expect($request->messages())->toHaveKey('galleryImages.*.dimensions');
});

test('has custom error message for gallery images max count', function () {
    $request = new UpdateProjectMediaRequest();

    expect($request->messages())->toHaveKey('galleryImages.max');
});

test('authorize returns true', function () {
    $request = new UpdateProjectMediaRequest();

    expect($request->authorize())->toBeTrue();
});