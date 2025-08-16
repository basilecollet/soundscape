<?php

use App\Livewire\Components\HomeSection;
use App\Models\PageContent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('home section retrieves content from database', function () {
    // Arrange
    PageContent::create([
        'key' => 'home_text',
        'content' => 'Welcome to Soundscape - Your audio journey starts here',
        'title' => 'Home Text',
        'page' => 'home',
    ]);

    // Act & Assert
    Livewire::test(HomeSection::class)
        ->assertSee('Welcome to Soundscape - Your audio journey starts here')
        ->assertStatus(200);
});

test('home section handles missing content gracefully', function () {
    // Arrange: No content in database

    // Act & Assert
    Livewire::test(HomeSection::class)
        ->assertStatus(200)
        ->assertViewHas('homeContent', '');
});

test('home section component renders the correct view', function () {
    // Act & Assert
    Livewire::test(HomeSection::class)
        ->assertViewIs('livewire.components.home-section');
});

test('home section updates when content changes', function () {
    // Arrange - Create initial content
    $content = PageContent::create([
        'key' => 'home_text',
        'content' => 'Original content',
        'title' => 'Home Text',
        'page' => 'home',
    ]);

    // Act - Test with original content
    Livewire::test(HomeSection::class)
        ->assertSee('Original content');

    // Update content
    $content->update(['content' => 'Updated content']);

    // Assert - Test with updated content
    Livewire::test(HomeSection::class)
        ->assertSee('Updated content')
        ->assertDontSee('Original content');
});
