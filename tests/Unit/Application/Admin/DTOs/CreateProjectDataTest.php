<?php

declare(strict_types=1);

use App\Application\Admin\DTOs\CreateProjectData;

test('can create CreateProjectData with only title', function () {
    $data = CreateProjectData::fromArray([
        'title' => 'My Project',
    ]);

    expect($data)->toBeInstanceOf(CreateProjectData::class)
        ->and($data->title)->toBe('My Project')
        ->and($data->description)->toBeNull()
        ->and($data->shortDescription)->toBeNull()
        ->and($data->clientName)->toBeNull()
        ->and($data->projectDate)->toBeNull();
});

test('can create CreateProjectData with all fields', function () {
    $data = CreateProjectData::fromArray([
        'title' => 'My Project',
        'description' => 'Long description',
        'short_description' => 'Short desc',
        'client_name' => 'Acme Corporation',
        'project_date' => '2024-06-15',
    ]);

    expect($data)->toBeInstanceOf(CreateProjectData::class)
        ->and($data->title)->toBe('My Project')
        ->and($data->description)->toBe('Long description')
        ->and($data->shortDescription)->toBe('Short desc')
        ->and($data->clientName)->toBe('Acme Corporation')
        ->and($data->projectDate)->toBe('2024-06-15');
});

test('can create CreateProjectData with partial fields', function () {
    $data = CreateProjectData::fromArray([
        'title' => 'My Project',
        'description' => 'Some description',
        'client_name' => 'Client Inc',
    ]);

    expect($data->title)->toBe('My Project')
        ->and($data->description)->toBe('Some description')
        ->and($data->shortDescription)->toBeNull()
        ->and($data->clientName)->toBe('Client Inc')
        ->and($data->projectDate)->toBeNull();
});

test('can convert CreateProjectData to array', function () {
    $data = CreateProjectData::fromArray([
        'title' => 'My Project',
        'description' => 'Long description',
        'short_description' => 'Short desc',
        'client_name' => 'Acme Corporation',
        'project_date' => '2024-06-15',
    ]);

    $array = $data->toArray();

    expect($array)->toBe([
        'title' => 'My Project',
        'description' => 'Long description',
        'short_description' => 'Short desc',
        'client_name' => 'Acme Corporation',
        'project_date' => '2024-06-15',
    ]);
});

test('toArray includes null values', function () {
    $data = CreateProjectData::fromArray([
        'title' => 'My Project',
    ]);

    $array = $data->toArray();

    expect($array)->toBe([
        'title' => 'My Project',
        'description' => null,
        'short_description' => null,
        'client_name' => null,
        'project_date' => null,
    ]);
});
