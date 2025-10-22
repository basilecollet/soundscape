<?php

declare(strict_types=1);

use App\Domain\Portfolio\Entities\Image;
use App\Domain\Portfolio\Exceptions\InvalidImageException;

test('can create an image with required URLs', function () {
    $image = new Image(
        webUrl: 'https://example.com/image-web.jpg',
        thumbUrl: 'https://example.com/image-thumb.jpg'
    );

    expect($image)->toBeInstanceOf(Image::class)
        ->and($image->webUrl)->toBe('https://example.com/image-web.jpg')
        ->and($image->thumbUrl)->toBe('https://example.com/image-thumb.jpg')
        ->and($image->alt)->toBeNull();
});

test('can create an image with alt text', function () {
    $image = new Image(
        webUrl: 'https://example.com/image-web.jpg',
        thumbUrl: 'https://example.com/image-thumb.jpg',
        alt: 'A beautiful landscape'
    );

    expect($image->alt)->toBe('A beautiful landscape');
});

test('web URL cannot be empty', function () {
    expect(fn () => new Image(
        webUrl: '',
        thumbUrl: 'https://example.com/image-thumb.jpg'
    ))->toThrow(InvalidImageException::class, 'Image web URL cannot be empty');
});

test('thumb URL cannot be empty', function () {
    expect(fn () => new Image(
        webUrl: 'https://example.com/image-web.jpg',
        thumbUrl: ''
    ))->toThrow(InvalidImageException::class, 'Image thumbnail URL cannot be empty');
});

test('web URL must be valid format', function () {
    expect(fn () => new Image(
        webUrl: 'not-a-valid-url',
        thumbUrl: 'https://example.com/image-thumb.jpg'
    ))->toThrow(InvalidImageException::class, 'Invalid URL format');
});

test('thumb URL must be valid format', function () {
    expect(fn () => new Image(
        webUrl: 'https://example.com/image-web.jpg',
        thumbUrl: 'invalid-url'
    ))->toThrow(InvalidImageException::class, 'Invalid URL format');
});

test('alt text can be null', function () {
    $image = new Image(
        webUrl: 'https://example.com/image-web.jpg',
        thumbUrl: 'https://example.com/image-thumb.jpg',
        alt: null
    );

    expect($image->alt)->toBeNull();
});

test('alt text can be empty string', function () {
    $image = new Image(
        webUrl: 'https://example.com/image-web.jpg',
        thumbUrl: 'https://example.com/image-thumb.jpg',
        alt: ''
    );

    expect($image->alt)->toBe('');
});

test('image is readonly', function () {
    $image = new Image(
        webUrl: 'https://example.com/image-web.jpg',
        thumbUrl: 'https://example.com/image-thumb.jpg'
    );

    $reflection = new ReflectionClass($image);
    expect($reflection->isReadOnly())->toBeTrue();
});
