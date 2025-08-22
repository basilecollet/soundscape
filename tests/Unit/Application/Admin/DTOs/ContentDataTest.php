<?php

declare(strict_types=1);

use App\Application\Admin\DTOs\ContentData;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can create ContentData for content creation', function () {
    $data = ContentData::forCreation(
        key: 'home_hero',
        content: 'Welcome to our site',
        page: 'home',
        title: 'Hero Section'
    );

    expect($data->id)->toBeNull();
    expect($data->key)->toBe('home_hero');
    expect($data->content)->toBe('Welcome to our site');
    expect($data->page)->toBe('home');
    expect($data->title)->toBe('Hero Section');
    expect($data->isCreation())->toBeTrue();
});

it('can create ContentData for content creation without title', function () {
    $data = ContentData::forCreation(
        key: 'home_hero',
        content: 'Welcome to our site',
        page: 'home'
    );

    expect($data->id)->toBeNull();
    expect($data->key)->toBe('home_hero');
    expect($data->content)->toBe('Welcome to our site');
    expect($data->page)->toBe('home');
    expect($data->title)->toBeNull();
    expect($data->isCreation())->toBeTrue();
});

it('can create ContentData for content update', function () {
    $data = ContentData::forUpdate(
        id: 123,
        content: 'Updated content',
        title: 'Updated Title'
    );

    expect($data->id)->toBe(123);
    expect($data->content)->toBe('Updated content');
    expect($data->title)->toBe('Updated Title');
    expect($data->key)->toBeNull();
    expect($data->page)->toBeNull();
    expect($data->isCreation())->toBeFalse();
});

it('can create ContentData for content update without title', function () {
    $data = ContentData::forUpdate(
        id: 123,
        content: 'Updated content'
    );

    expect($data->id)->toBe(123);
    expect($data->content)->toBe('Updated content');
    expect($data->title)->toBeNull();
    expect($data->isCreation())->toBeFalse();
});

it('can create ContentData from array for creation', function () {
    $array = [
        'content' => 'Test content',
        'title' => 'Test Title',
        'key' => 'home_text',
        'page' => 'home',
    ];

    $data = ContentData::fromArray($array);

    expect($data->id)->toBeNull();
    expect($data->content)->toBe('Test content');
    expect($data->title)->toBe('Test Title');
    expect($data->key)->toBe('home_text');
    expect($data->page)->toBe('home');
    expect($data->isCreation())->toBeTrue();
});

it('can create ContentData from array for update', function () {
    $array = [
        'id' => 456,
        'content' => 'Updated test content',
        'title' => 'Updated Test Title',
    ];

    $data = ContentData::fromArray($array);

    expect($data->id)->toBe(456);
    expect($data->content)->toBe('Updated test content');
    expect($data->title)->toBe('Updated Test Title');
    expect($data->key)->toBeNull();
    expect($data->page)->toBeNull();
    expect($data->isCreation())->toBeFalse();
});

it('can convert ContentData to array for creation', function () {
    $data = ContentData::forCreation(
        key: 'about_section_1',
        content: 'About us content',
        page: 'about',
        title: 'About Section'
    );

    $array = $data->toArray();

    expect($array)->toBe([
        'id' => null,
        'content' => 'About us content',
        'title' => 'About Section',
        'key' => 'about_section_1',
        'page' => 'about',
    ]);
});

it('can convert ContentData to array for update', function () {
    $data = ContentData::forUpdate(
        id: 789,
        content: 'Updated about content',
        title: 'Updated About'
    );

    $array = $data->toArray();

    expect($array)->toBe([
        'id' => 789,
        'content' => 'Updated about content',
        'title' => 'Updated About',
        'key' => null,
        'page' => null,
    ]);
});

it('can handle array without optional fields', function () {
    $array = [
        'content' => 'Minimal content',
        'key' => 'contact_text',
        'page' => 'contact',
    ];

    $data = ContentData::fromArray($array);

    expect($data->content)->toBe('Minimal content');
    expect($data->title)->toBeNull();
    expect($data->key)->toBe('contact_text');
    expect($data->page)->toBe('contact');
});
