<?php

declare(strict_types=1);

use App\Domain\Portfolio\ValueObjects\PageField;
use App\Infra\Repositories\Portfolio\PageContentEloquentRepository;
use App\Models\PageContent;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('it retrieves all fields for a page', function () {
    // Given: Multiple page contents exist
    PageContent::factory()->create(['key' => 'home_hero_title', 'content' => 'Title', 'page' => 'home']);
    PageContent::factory()->create(['key' => 'home_hero_subtitle', 'content' => 'Subtitle', 'page' => 'home']);
    PageContent::factory()->create(['key' => 'about_title', 'content' => 'About', 'page' => 'about']);

    $repository = new PageContentEloquentRepository;

    // When
    $fields = $repository->getFieldsForPage('home');

    // Then
    expect($fields)->toHaveCount(2)
        ->and($fields[0])->toBeInstanceOf(PageField::class)
        ->and($fields[0]->getKey())->toBe('home_hero_title')
        ->and($fields[0]->getContent())->toBe('Title')
        ->and($fields[1]->getKey())->toBe('home_hero_subtitle')
        ->and($fields[1]->getContent())->toBe('Subtitle');
});

test('it returns empty array when page has no content', function () {
    $repository = new PageContentEloquentRepository;

    $fields = $repository->getFieldsForPage('nonexistent');

    expect($fields)->toBeEmpty();
});

test('it handles empty content correctly', function () {
    PageContent::factory()->create(['key' => 'home_hero_title', 'content' => '', 'page' => 'home']);

    $repository = new PageContentEloquentRepository;

    $field = $repository->getFieldsForPage('home')[0];

    expect($field)->toBeInstanceOf(PageField::class)
        ->and($field->isEmpty())->toBeTrue()
        ->and($field->getContent())->toBe('');
});

test('it only returns fields for specified page', function () {
    // Given: Multiple pages exist
    PageContent::factory()->create(['key' => 'home_hero_title', 'content' => 'Home', 'page' => 'home']);
    PageContent::factory()->create(['key' => 'about_title', 'content' => 'About', 'page' => 'about']);
    PageContent::factory()->create(['key' => 'contact_title', 'content' => 'Contact', 'page' => 'contact']);

    $repository = new PageContentEloquentRepository;

    // When: Get fields for 'about' page
    $fields = $repository->getFieldsForPage('about');

    // Then: Only 'about' fields returned
    expect($fields)->toHaveCount(1)
        ->and($fields[0]->getKey())->toBe('about_title')
        ->and($fields[0]->getContent())->toBe('About');
});

test('it transforms all PageContent models to PageField value objects', function () {
    PageContent::factory()->create(['key' => 'home_hero_title', 'content' => 'Title 1', 'page' => 'home']);
    PageContent::factory()->create(['key' => 'home_hero_subtitle', 'content' => 'Title 2', 'page' => 'home']);
    PageContent::factory()->create(['key' => 'home_hero_text', 'content' => 'Title 3', 'page' => 'home']);

    $repository = new PageContentEloquentRepository;

    $fields = $repository->getFieldsForPage('home');

    expect($fields)->toHaveCount(3)
        ->and($fields)->each(fn ($field) => $field->toBeInstanceOf(PageField::class));
});
