<?php

declare(strict_types=1);

use App\Application\Admin\DTOs\UpdateProjectData;

test('can create UpdateProjectData with slug and title only', function () {
    $data = UpdateProjectData::fromArray([
        'slug' => 'my-project',
        'title' => 'Updated Title',
    ]);

    expect($data)->toBeInstanceOf(UpdateProjectData::class)
        ->and($data->slug)->toBe('my-project')
        ->and($data->title)->toBe('Updated Title')
        ->and($data->description)->toBeNull()
        ->and($data->shortDescription)->toBeNull()
        ->and($data->clientName)->toBeNull()
        ->and($data->projectDate)->toBeNull();
});

test('can create UpdateProjectData with all fields', function () {
    $data = UpdateProjectData::fromArray([
        'slug' => 'my-project',
        'title' => 'Updated Project',
        'description' => 'New description',
        'short_description' => 'New short',
        'client_name' => 'New Client Inc',
        'project_date' => '2024-12-25',
    ]);

    expect($data)->toBeInstanceOf(UpdateProjectData::class)
        ->and($data->slug)->toBe('my-project')
        ->and($data->title)->toBe('Updated Project')
        ->and($data->description)->toBe('New description')
        ->and($data->shortDescription)->toBe('New short')
        ->and($data->clientName)->toBe('New Client Inc')
        ->and($data->projectDate)->toBe('2024-12-25');
});

test('can create UpdateProjectData with partial fields', function () {
    $data = UpdateProjectData::fromArray([
        'slug' => 'existing-project',
        'title' => 'Updated Title',
        'description' => 'Updated description',
    ]);

    expect($data->slug)->toBe('existing-project')
        ->and($data->title)->toBe('Updated Title')
        ->and($data->description)->toBe('Updated description')
        ->and($data->shortDescription)->toBeNull()
        ->and($data->clientName)->toBeNull()
        ->and($data->projectDate)->toBeNull();
});

test('can convert UpdateProjectData to array', function () {
    $data = UpdateProjectData::fromArray([
        'slug' => 'my-project',
        'title' => 'Updated Project',
        'description' => 'New description',
        'short_description' => 'New short',
        'client_name' => 'New Client',
        'project_date' => '2024-12-25',
    ]);

    $array = $data->toArray();

    expect($array)->toBe([
        'slug' => 'my-project',
        'title' => 'Updated Project',
        'description' => 'New description',
        'short_description' => 'New short',
        'client_name' => 'New Client',
        'project_date' => '2024-12-25',
    ]);
});

test('toArray includes null values', function () {
    $data = UpdateProjectData::fromArray([
        'slug' => 'project-slug',
        'title' => 'Just Title',
    ]);

    $array = $data->toArray();

    expect($array)->toBe([
        'slug' => 'project-slug',
        'title' => 'Just Title',
        'description' => null,
        'short_description' => null,
        'client_name' => null,
        'project_date' => null,
    ]);
});