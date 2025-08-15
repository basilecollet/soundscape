<?php

use App\Models\PageContent;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

/**
 * TDD Example: PageContent Model Tests
 *
 * Tests unitaires pour le modèle PageContent
 * suivant l'approche TDD
 */
test('page content can be created with required fields', function () {
    // Arrange & Act
    $content = PageContent::create([
        'key' => 'test_key',
        'content' => 'Test content',
        'title' => 'Test Title',
        'page' => 'home',
    ]);

    // Assert
    expect($content)->toBeInstanceOf(PageContent::class)
        ->and($content->key)->toBe('test_key')
        ->and($content->content)->toBe('Test content')
        ->and($content->title)->toBe('Test Title')
        ->and($content->page)->toBe('home');
});

test('page content can be retrieved by key', function () {
    // Arrange
    PageContent::create([
        'key' => 'home_text',
        'content' => 'Welcome to our site',
        'title' => 'Home Text',
        'page' => 'home',
    ]);

    // Act
    $content = PageContent::getContent('home_text');

    // Assert
    expect($content)->toBe('Welcome to our site');
});

test('getContent returns empty string when key does not exist', function () {
    // Act
    $content = PageContent::getContent('non_existent_key');

    // Assert
    expect($content)->toBe('');
});

test('page content key must be unique', function () {
    // Arrange
    PageContent::create([
        'key' => 'unique_key',
        'content' => 'First content',
        'title' => 'First',
        'page' => 'home',
    ]);

    // Act & Assert
    expect(fn () => PageContent::create([
        'key' => 'unique_key',
        'content' => 'Second content',
        'title' => 'Second',
        'page' => 'about',
    ]))->toThrow(\Illuminate\Database\QueryException::class);
});

test('page content can be updated', function () {
    // Arrange
    $content = PageContent::create([
        'key' => 'update_test',
        'content' => 'Original content',
        'title' => 'Original',
        'page' => 'home',
    ]);

    // Act
    $content->update([
        'content' => 'Updated content',
    ]);

    // Assert
    expect($content->fresh()->content)->toBe('Updated content');
});

test('multiple contents can be retrieved for a specific page', function () {
    // Arrange
    PageContent::create(['key' => 'about_1', 'content' => 'Content 1', 'title' => 'About 1', 'page' => 'about']);
    PageContent::create(['key' => 'about_2', 'content' => 'Content 2', 'title' => 'About 2', 'page' => 'about']);
    PageContent::create(['key' => 'home_1', 'content' => 'Home content', 'title' => 'Home 1', 'page' => 'home']);

    // Act
    $aboutContents = PageContent::where('page', 'about')->get();

    // Assert
    expect($aboutContents)->toHaveCount(2)
        ->and($aboutContents->pluck('key')->toArray())->toBe(['about_1', 'about_2']);
});
