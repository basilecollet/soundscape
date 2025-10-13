<?php

declare(strict_types=1);

use App\Domain\Admin\Services\StringNormalizationService;

test('normalizeToNullable returns null for null input', function () {
    $service = new StringNormalizationService();

    expect($service->normalizeToNullable(null))->toBeNull();
});

test('normalizeToNullable returns null for empty string', function () {
    $service = new StringNormalizationService();

    expect($service->normalizeToNullable(''))->toBeNull();
});

test('normalizeToNullable returns null for whitespace only string', function () {
    $service = new StringNormalizationService();

    expect($service->normalizeToNullable('   '))->toBeNull();
});

test('normalizeToNullable returns null for tabs and newlines only', function () {
    $service = new StringNormalizationService();

    expect($service->normalizeToNullable("\t\n\r"))->toBeNull();
});

test('normalizeToNullable preserves non-empty string', function () {
    $service = new StringNormalizationService();

    expect($service->normalizeToNullable('hello'))->toBe('hello');
});

test('normalizeToNullable preserves string with spaces', function () {
    $service = new StringNormalizationService();

    expect($service->normalizeToNullable('hello world'))->toBe('hello world');
});

test('normalizeToNullable preserves string with leading and trailing content', function () {
    $service = new StringNormalizationService();

    expect($service->normalizeToNullable('  hello  '))->toBe('  hello  ');
});