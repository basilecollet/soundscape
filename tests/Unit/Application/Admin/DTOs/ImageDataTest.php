<?php

declare(strict_types=1);

use App\Application\Admin\DTOs\ImageData;
use App\Domain\Admin\Entities\Image;

test('can create ImageData from Image entity', function () {
    $image = new Image(
        originalUrl: 'https://example.com/image.jpg',
        thumbUrl: 'https://example.com/image-thumb.jpg',
        webUrl: 'https://example.com/image-web.jpg',
        previewUrl: 'https://example.com/image-preview.jpg',
        alt: 'Test image'
    );

    $imageData = ImageData::fromEntity($image);

    expect($imageData)->toBeInstanceOf(ImageData::class)
        ->and($imageData->originalUrl)->toBe('https://example.com/image.jpg')
        ->and($imageData->thumbUrl)->toBe('https://example.com/image-thumb.jpg')
        ->and($imageData->webUrl)->toBe('https://example.com/image-web.jpg')
        ->and($imageData->previewUrl)->toBe('https://example.com/image-preview.jpg')
        ->and($imageData->alt)->toBe('Test image');
});

test('can create ImageData from Image entity without alt', function () {
    $image = new Image(
        originalUrl: 'https://example.com/image.jpg',
        thumbUrl: 'https://example.com/image-thumb.jpg',
        webUrl: 'https://example.com/image-web.jpg',
        previewUrl: 'https://example.com/image-preview.jpg'
    );

    $imageData = ImageData::fromEntity($image);

    expect($imageData->alt)->toBeNull();
});

test('can convert ImageData to array', function () {
    $image = new Image(
        originalUrl: 'https://example.com/image.jpg',
        thumbUrl: 'https://example.com/image-thumb.jpg',
        webUrl: 'https://example.com/image-web.jpg',
        previewUrl: 'https://example.com/image-preview.jpg',
        alt: 'Test image'
    );

    $imageData = ImageData::fromEntity($image);
    $array = $imageData->toArray();

    expect($array)->toHaveKey('original_url')
        ->and($array)->toHaveKey('thumb_url')
        ->and($array)->toHaveKey('web_url')
        ->and($array)->toHaveKey('preview_url')
        ->and($array)->toHaveKey('alt')
        ->and($array['original_url'])->toBe('https://example.com/image.jpg')
        ->and($array['thumb_url'])->toBe('https://example.com/image-thumb.jpg')
        ->and($array['web_url'])->toBe('https://example.com/image-web.jpg')
        ->and($array['preview_url'])->toBe('https://example.com/image-preview.jpg')
        ->and($array['alt'])->toBe('Test image');
});

test('toArray includes null for alt when not set', function () {
    $image = new Image(
        originalUrl: 'https://example.com/image.jpg',
        thumbUrl: 'https://example.com/image-thumb.jpg',
        webUrl: 'https://example.com/image-web.jpg',
        previewUrl: 'https://example.com/image-preview.jpg'
    );

    $imageData = ImageData::fromEntity($image);
    $array = $imageData->toArray();

    expect($array['alt'])->toBeNull();
});
