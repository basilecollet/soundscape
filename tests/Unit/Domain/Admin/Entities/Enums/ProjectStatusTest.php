<?php

declare(strict_types=1);

use App\Domain\Admin\Entities\Enums\ProjectStatus;

test('has all expected status cases', function () {
    $cases = ProjectStatus::cases();

    expect($cases)->toHaveCount(3)
        ->and($cases[0])->toBe(ProjectStatus::Draft)
        ->and($cases[1])->toBe(ProjectStatus::Published)
        ->and($cases[2])->toBe(ProjectStatus::Archived);
});

test('can get status values as strings', function () {
    expect(ProjectStatus::Draft->value)->toBe('draft')
        ->and(ProjectStatus::Published->value)->toBe('published')
        ->and(ProjectStatus::Archived->value)->toBe('archived');
});

test('can check if status is draft', function () {
    expect(ProjectStatus::Draft->isDraft())->toBeTrue()
        ->and(ProjectStatus::Published->isDraft())->toBeFalse()
        ->and(ProjectStatus::Archived->isDraft())->toBeFalse();
});

test('can check if status is published', function () {
    expect(ProjectStatus::Published->isPublished())->toBeTrue()
        ->and(ProjectStatus::Draft->isPublished())->toBeFalse()
        ->and(ProjectStatus::Archived->isPublished())->toBeFalse();
});

test('can check if status is archived', function () {
    expect(ProjectStatus::Archived->isArchived())->toBeTrue()
        ->and(ProjectStatus::Draft->isArchived())->toBeFalse()
        ->and(ProjectStatus::Published->isArchived())->toBeFalse();
});

test('can get readable label for each status', function () {
    expect(ProjectStatus::Draft->label())->toBe('Draft')
        ->and(ProjectStatus::Published->label())->toBe('Published')
        ->and(ProjectStatus::Archived->label())->toBe('Archived');
});

test('can create status from string value', function () {
    expect(ProjectStatus::from('draft'))->toBe(ProjectStatus::Draft)
        ->and(ProjectStatus::from('published'))->toBe(ProjectStatus::Published)
        ->and(ProjectStatus::from('archived'))->toBe(ProjectStatus::Archived);
});