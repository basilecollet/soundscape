<?php

declare(strict_types=1);

use App\Domain\Admin\Entities\ValueObjects\ProjectShortDescription;

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
