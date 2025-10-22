<?php

declare(strict_types=1);

use App\Domain\Portfolio\Entities\ValueObjects\ProjectTitle;
use App\Domain\Portfolio\Exceptions\InvalidProjectTitleException;

test('it creates a valid project title', function () {
    $title = ProjectTitle::fromString('My Project');

    expect($title)->toBeInstanceOf(ProjectTitle::class)
        ->and((string) $title)->toBe('My Project');
});

test('it trims whitespace from title', function () {
    $title = ProjectTitle::fromString('  My Project  ');

    expect((string) $title)->toBe('My Project');
});

test('it throws exception when title is empty string', function () {
    expect(fn () => ProjectTitle::fromString(''))
        ->toThrow(InvalidProjectTitleException::class, 'Project title cannot be empty');
});

test('it throws exception when title is only whitespace', function () {
    expect(fn () => ProjectTitle::fromString('   '))
        ->toThrow(InvalidProjectTitleException::class, 'Project title cannot be empty');
});

test('it throws exception when title exceeds 255 characters', function () {
    $longTitle = str_repeat('a', 256);

    expect(fn () => ProjectTitle::fromString($longTitle))
        ->toThrow(InvalidProjectTitleException::class, 'Project title cannot exceed 255 characters');
});

test('it accepts title with exactly 255 characters', function () {
    $title = str_repeat('a', 255);
    $projectTitle = ProjectTitle::fromString($title);

    expect((string) $projectTitle)->toBe($title)
        ->and(mb_strlen((string) $projectTitle))->toBe(255);
});

test('it accepts title with unicode characters', function () {
    $title = ProjectTitle::fromString('Café & Thé ☕ 2024');

    expect((string) $title)->toBe('Café & Thé ☕ 2024');
});
