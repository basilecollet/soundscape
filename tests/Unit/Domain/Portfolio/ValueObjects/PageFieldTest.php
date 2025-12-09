<?php

declare(strict_types=1);

use App\Domain\Portfolio\ValueObjects\PageField;

test('it creates a page field with content', function () {
    $field = PageField::fromKeyAndContent('home_hero_title', 'Soundscape Audio');

    expect($field->isEmpty())->toBeFalse()
        ->and($field->getKey())->toBe('home_hero_title')
        ->and($field->getContent())->toBe('Soundscape Audio')
        ->and((string) $field)->toBe('Soundscape Audio');
});

test('it identifies empty field when content is null', function () {
    $field = PageField::fromKeyAndContent('home_hero_title', null);

    expect($field->isEmpty())->toBeTrue()
        ->and($field->getContent())->toBeNull()
        ->and((string) $field)->toBe('');
});

test('it identifies empty field when content is empty string', function () {
    $field = PageField::fromKeyAndContent('home_hero_title', '');

    expect($field->isEmpty())->toBeTrue()
        ->and($field->getContent())->toBe('')
        ->and((string) $field)->toBe('');
});

test('it identifies empty field when content is only whitespace', function () {
    $field = PageField::fromKeyAndContent('home_hero_title', '   ');

    expect($field->isEmpty())->toBeTrue()
        ->and((string) $field)->toBe('   '); // Preserves original
});

test('it considers field with whitespace-wrapped content as non-empty', function () {
    $field = PageField::fromKeyAndContent('home_hero_title', '  Soundscape  ');

    expect($field->isEmpty())->toBeFalse()
        ->and((string) $field)->toBe('  Soundscape  '); // Preserves original
});

test('it correctly handles content with line breaks', function () {
    $content = "Line 1\nLine 2";
    $field = PageField::fromKeyAndContent('about_bio', $content);

    expect($field->isEmpty())->toBeFalse()
        ->and($field->getContent())->toBe($content);
});
