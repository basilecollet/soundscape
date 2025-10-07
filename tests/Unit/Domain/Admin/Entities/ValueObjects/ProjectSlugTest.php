<?php

declare(strict_types=1);

use App\Domain\Admin\Entities\ValueObjects\ProjectSlug;
use App\Domain\Admin\Entities\ValueObjects\ProjectTitle;

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
