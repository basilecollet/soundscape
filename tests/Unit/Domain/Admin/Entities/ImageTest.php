<?php

declare(strict_types=1);

use App\Domain\Admin\Entities\Image;
use App\Domain\Admin\Exceptions\InvalidImageException;

test('create an image with all URLs', function () {
    $image = new Image(
        originalUrl: 'https://example.com/image.jpg',
        thumbUrl: 'https://example.com/image-thumb.jpg',
        webUrl: 'https://example.com/image-web.jpg',
        previewUrl: 'https://example.com/image-preview.jpg'
    );

    expect($image)->toBeInstanceOf(Image::class)
        ->and($image->originalUrl)->toBe('https://example.com/image.jpg')
        ->and($image->thumbUrl)->toBe('https://example.com/image-thumb.jpg')
        ->and($image->webUrl)->toBe('https://example.com/image-web.jpg')
        ->and($image->previewUrl)->toBe('https://example.com/image-preview.jpg')
        ->and($image->alt)->toBeNull();
});

test('create an image with alt text', function () {
    $image = new Image(
        originalUrl: 'https://example.com/image.jpg',
        thumbUrl: 'https://example.com/image-thumb.jpg',
        webUrl: 'https://example.com/image-web.jpg',
        previewUrl: 'https://example.com/image-preview.jpg',
        alt: 'A beautiful landscape'
    );

    expect($image->alt)->toBe('A beautiful landscape');
});

test('image original URL cannot be empty', function () {
    expect(fn () => new Image(
        originalUrl: '',
        thumbUrl: 'https://example.com/image-thumb.jpg',
        webUrl: 'https://example.com/image-web.jpg',
        previewUrl: 'https://example.com/image-preview.jpg'
    ))->toThrow(InvalidImageException::class, 'Image original URL cannot be empty');
});

test('thumb URL cannot be empty', function () {
    expect(fn () => new Image(
        originalUrl: 'https://example.com/image.jpg',
        thumbUrl: '',
        webUrl: 'https://example.com/image-web.jpg',
        previewUrl: 'https://example.com/image-preview.jpg'
    ))->toThrow(InvalidImageException::class, 'Image thumbnail URL cannot be empty');
});

test('web URL cannot be empty', function () {
    expect(fn () => new Image(
        originalUrl: 'https://example.com/image.jpg',
        thumbUrl: 'https://example.com/image-thumb.jpg',
        webUrl: '',
        previewUrl: 'https://example.com/image-preview.jpg'
    ))->toThrow(InvalidImageException::class, 'Image web URL cannot be empty');
});

test('preview URL cannot be empty', function () {
    expect(fn () => new Image(
        originalUrl: 'https://example.com/image.jpg',
        thumbUrl: 'https://example.com/image-thumb.jpg',
        webUrl: 'https://example.com/image-web.jpg',
        previewUrl: ''
    ))->toThrow(InvalidImageException::class, 'Image preview URL cannot be empty');
});

test('original URL must be valid format', function () {
    expect(fn () => new Image(
        originalUrl: 'not-a-valid-url',
        thumbUrl: 'https://example.com/image-thumb.jpg',
        webUrl: 'https://example.com/image-web.jpg',
        previewUrl: 'https://example.com/image-preview.jpg'
    ))->toThrow(InvalidImageException::class, 'Invalid URL format');
});

test('thumb URL must be valid format', function () {
    expect(fn () => new Image(
        originalUrl: 'https://example.com/image.jpg',
        thumbUrl: 'invalid-url',
        webUrl: 'https://example.com/image-web.jpg',
        previewUrl: 'https://example.com/image-preview.jpg'
    ))->toThrow(InvalidImageException::class, 'Invalid URL format');
});

test('web URL must be valid format', function () {
    expect(fn () => new Image(
        originalUrl: 'https://example.com/image.jpg',
        thumbUrl: 'https://example.com/image-thumb.jpg',
        webUrl: 'not-valid',
        previewUrl: 'https://example.com/image-preview.jpg'
    ))->toThrow(InvalidImageException::class, 'Invalid URL format');
});

test('preview URL must be valid format', function () {
    expect(fn () => new Image(
        originalUrl: 'https://example.com/image.jpg',
        thumbUrl: 'https://example.com/image-thumb.jpg',
        webUrl: 'https://example.com/image-web.jpg',
        previewUrl: 'bad-url'
    ))->toThrow(InvalidImageException::class, 'Invalid URL format');
});

test('alt text can be null', function () {
    $image = new Image(
        originalUrl: 'https://example.com/image.jpg',
        thumbUrl: 'https://example.com/image-thumb.jpg',
        webUrl: 'https://example.com/image-web.jpg',
        previewUrl: 'https://example.com/image-preview.jpg',
        alt: null
    );

    expect($image->alt)->toBeNull();
});

test('alt text can be empty string', function () {
    $image = new Image(
        originalUrl: 'https://example.com/image.jpg',
        thumbUrl: 'https://example.com/image-thumb.jpg',
        webUrl: 'https://example.com/image-web.jpg',
        previewUrl: 'https://example.com/image-preview.jpg',
        alt: ''
    );

    expect($image->alt)->toBe('');
});
