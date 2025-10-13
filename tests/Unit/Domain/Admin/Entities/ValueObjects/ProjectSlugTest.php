<?php

declare(strict_types=1);

use App\Domain\Admin\Entities\ValueObjects\ProjectSlug;
use App\Domain\Admin\Entities\ValueObjects\ProjectTitle;
use App\Domain\Admin\Exceptions\InvalidProjectSlugException;

test('create a slug from a project title', function () {
    $title = ProjectTitle::fromString('My Project');
    $slug = ProjectSlug::fromTitle($title);

    expect($slug)->toBeInstanceOf(ProjectSlug::class)
        ->and((string) $slug)->toBe('my-project');
});

test('generate slug with special characters and accents', function () {
    $title = ProjectTitle::fromString('Mon Projet Été 2024 !');
    $slug = ProjectSlug::fromTitle($title);

    expect((string) $slug)->toBe('mon-projet-ete-2024');
});

test('generate slug with multiple spaces and special chars', function () {
    $title = ProjectTitle::fromString('Hello   World  &  Test!!!');
    $slug = ProjectSlug::fromTitle($title);

    expect((string) $slug)->toBe('hello-world-test');
});

test('generate slug with unicode and special characters', function () {
    $title = ProjectTitle::fromString('Café & Thé ☕ 2024');
    $slug = ProjectSlug::fromTitle($title);

    expect((string) $slug)->toBe('cafe-the-2024');
});

test('can create slug from string', function () {
    $slug = ProjectSlug::fromString('existing-project-slug');

    expect($slug)->toBeInstanceOf(ProjectSlug::class)
        ->and((string) $slug)->toBe('existing-project-slug');
});

test('fromString preserves slug as-is', function () {
    $slug = ProjectSlug::fromString('my-custom-slug-123');

    expect((string) $slug)->toBe('my-custom-slug-123');
});

test('fromString accepts valid slug with lowercase letters', function () {
    $slug = ProjectSlug::fromString('simple-slug');

    expect((string) $slug)->toBe('simple-slug');
});

test('fromString accepts valid slug with numbers', function () {
    $slug = ProjectSlug::fromString('project-2024-v2');

    expect((string) $slug)->toBe('project-2024-v2');
});

test('fromString rejects slug with uppercase letters', function () {
    expect(fn () => ProjectSlug::fromString('Invalid-Slug'))
        ->toThrow(InvalidProjectSlugException::class);
});

test('fromString rejects slug with spaces', function () {
    expect(fn () => ProjectSlug::fromString('invalid slug'))
        ->toThrow(InvalidProjectSlugException::class);
});

test('fromString rejects slug with special characters', function () {
    expect(fn () => ProjectSlug::fromString('invalid_slug'))
        ->toThrow(InvalidProjectSlugException::class);
});

test('fromString rejects slug starting with dash', function () {
    expect(fn () => ProjectSlug::fromString('-invalid-slug'))
        ->toThrow(InvalidProjectSlugException::class);
});

test('fromString rejects slug ending with dash', function () {
    expect(fn () => ProjectSlug::fromString('invalid-slug-'))
        ->toThrow(InvalidProjectSlugException::class);
});

test('fromString rejects empty string', function () {
    expect(fn () => ProjectSlug::fromString(''))
        ->toThrow(InvalidProjectSlugException::class);
});
