<?php

declare(strict_types=1);

use App\Domain\Admin\Entities\ValueObjects\ProjectDate;
use App\Domain\Admin\Exceptions\InvalidProjectDateException;
use Carbon\Carbon;

test('can create project date from Carbon instance', function () {
    $carbon = Carbon::parse('2024-06-15');
    $projectDate = ProjectDate::fromCarbon($carbon);

    expect($projectDate)->toBeInstanceOf(ProjectDate::class);
});

test('can create project date from string', function () {
    $projectDate = ProjectDate::fromString('2024-06-15');

    expect($projectDate)->toBeInstanceOf(ProjectDate::class);
});

test('can convert project date to Carbon', function () {
    $projectDate = ProjectDate::fromString('2024-06-15');
    $carbon = $projectDate->toCarbon();

    expect($carbon)->toBeInstanceOf(Carbon::class)
        ->and($carbon->format('Y-m-d'))->toBe('2024-06-15');
});

test('can format project date as string', function () {
    $projectDate = ProjectDate::fromString('2024-06-15');

    expect($projectDate->format('Y-m-d'))->toBe('2024-06-15')
        ->and($projectDate->format('d/m/Y'))->toBe('15/06/2024');
});

test('two project dates with same date are equal', function () {
    $date1 = ProjectDate::fromString('2024-06-15');
    $date2 = ProjectDate::fromString('2024-06-15');

    expect($date1->toCarbon()->eq($date2->toCarbon()))->toBeTrue();
});

test('it throws exception when date format is invalid', function () {
    expect(fn () => ProjectDate::fromString('invalid-date'))
        ->toThrow(InvalidProjectDateException::class);
});

test('it throws exception for completely invalid date string', function () {
    expect(fn () => ProjectDate::fromString('not-a-date-at-all'))
        ->toThrow(InvalidProjectDateException::class);
});

test('it throws exception for empty date string', function () {
    expect(fn () => ProjectDate::fromString(''))
        ->toThrow(InvalidProjectDateException::class);
});

test('it throws exception for nonsensical date values', function () {
    expect(fn () => ProjectDate::fromString('2024-13-45'))
        ->toThrow(InvalidProjectDateException::class);
});
