<?php

declare(strict_types=1);

use App\Domain\Admin\Entities\ValueObjects\ProjectDescription;

test('can create description from string', function () {
    $description = ProjectDescription::fromString('This is a **markdown** description');

    expect($description)->toBeInstanceOf(ProjectDescription::class);
});

test('can convert description to string', function () {
    $description = ProjectDescription::fromString('This is a **markdown** description');

    expect((string) $description)->toBe('This is a **markdown** description');
});

test('can create empty description', function () {
    $description = ProjectDescription::fromString('');

    expect((string) $description)->toBe('');
});

test('description preserves markdown formatting', function () {
    $markdown = "# Title\n\n- Item 1\n- Item 2\n\n**Bold text**";
    $description = ProjectDescription::fromString($markdown);

    expect((string) $description)->toBe($markdown);
});

test('description can contain multiple lines', function () {
    $multiline = "First line\nSecond line\nThird line";
    $description = ProjectDescription::fromString($multiline);

    expect((string) $description)->toBe($multiline);
});

test('two descriptions with same content are equal', function () {
    $desc1 = ProjectDescription::fromString('Same content');
    $desc2 = ProjectDescription::fromString('Same content');

    expect($desc1)->toEqual($desc2);
});

test('description trims whitespace', function () {
    $description = ProjectDescription::fromString('  Content with spaces  ');

    expect((string) $description)->toBe('Content with spaces');
});
