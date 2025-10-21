<?php

declare(strict_types=1);

use App\Domain\Admin\Entities\Image;

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
    ))->toThrow(\InvalidArgumentException::class, 'Image URLs cannot be empty');
});

test('thumb URL cannot be empty', function () {
    expect(fn () => new Image(
        originalUrl: 'https://example.com/image.jpg',
        thumbUrl: '',
        webUrl: 'https://example.com/image-web.jpg',
        previewUrl: 'https://example.com/image-preview.jpg'
    ))->toThrow(\InvalidArgumentException::class, 'Image URLs cannot be empty');
});

test('web URL cannot be empty', function () {
    expect(fn () => new Image(
        originalUrl: 'https://example.com/image.jpg',
        thumbUrl: 'https://example.com/image-thumb.jpg',
        webUrl: '',
        previewUrl: 'https://example.com/image-preview.jpg'
    ))->toThrow(\InvalidArgumentException::class, 'Image URLs cannot be empty');
});

test('preview URL cannot be empty', function () {
    expect(fn () => new Image(
        originalUrl: 'https://example.com/image.jpg',
        thumbUrl: 'https://example.com/image-thumb.jpg',
        webUrl: 'https://example.com/image-web.jpg',
        previewUrl: ''
    ))->toThrow(\InvalidArgumentException::class, 'Image URLs cannot be empty');
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
