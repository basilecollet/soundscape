<?php

declare(strict_types=1);

use App\Domain\Portfolio\Entities\ValueObjects\ProjectShortDescription;
use App\Domain\Portfolio\Exceptions\InvalidProjectShortDescriptionException;

test('can create short description from string', function () {
    $shortDescription = ProjectShortDescription::fromString('A brief project summary');

    expect($shortDescription)->toBeInstanceOf(ProjectShortDescription::class);
});

test('can convert short description to string', function () {
    $shortDescription = ProjectShortDescription::fromString('A brief project summary');

    expect((string) $shortDescription)->toBe('A brief project summary');
});

test('short description trims whitespace', function () {
    $shortDescription = ProjectShortDescription::fromString('  Content with spaces  ');

    expect((string) $shortDescription)->toBe('Content with spaces');
});

test('two short descriptions with same content are equal', function () {
    $desc1 = ProjectShortDescription::fromString('Same content');
    $desc2 = ProjectShortDescription::fromString('Same content');

    expect($desc1)->toEqual($desc2);
});

test('it throws exception when short description is empty string', function () {
    expect(fn () => ProjectShortDescription::fromString(''))
        ->toThrow(InvalidProjectShortDescriptionException::class, 'Project short description cannot be empty');
});

test('it throws exception when short description is only whitespace', function () {
    expect(fn () => ProjectShortDescription::fromString('   '))
        ->toThrow(InvalidProjectShortDescriptionException::class, 'Project short description cannot be empty');
});

test('it throws exception when short description exceeds 500 characters', function () {
    $longDescription = str_repeat('a', 501);

    expect(fn () => ProjectShortDescription::fromString($longDescription))
        ->toThrow(InvalidProjectShortDescriptionException::class, 'Project short description cannot exceed 500 characters');
});

test('it accepts short description with exactly 500 characters', function () {
    $description = str_repeat('a', 500);
    $shortDescription = ProjectShortDescription::fromString($description);

    expect(mb_strlen((string) $shortDescription))->toBe(500);
});
