<?php

use App\Livewire\Components\AboutSection;
use App\Models\PageContent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('about section retrieves all three content sections from database', function () {
    // Arrange
    PageContent::create([
        'key' => 'about_section_1',
        'content' => 'First about section content',
        'title' => 'About Section 1',
        'page' => 'about',
    ]);

    PageContent::create([
        'key' => 'about_section_2',
        'content' => 'Second about section content',
        'title' => 'About Section 2',
        'page' => 'about',
    ]);

    PageContent::create([
        'key' => 'about_section_3',
        'content' => 'Third about section content',
        'title' => 'About Section 3',
        'page' => 'about',
    ]);

    // Act & Assert
    Livewire::test(AboutSection::class)
        ->assertSee('First about section content')
        ->assertSee('Second about section content')
        ->assertSee('Third about section content')
        ->assertStatus(200);
});

test('about section handles missing content gracefully', function () {
    // Arrange: No content in database

    // Act & Assert
    Livewire::test(AboutSection::class)
        ->assertStatus(200)
        ->assertViewHas('aboutContent', [
            'about_section_1' => '',
            'about_section_2' => '',
            'about_section_3' => '',
        ]);
});

test('about section handles partial content', function () {
    // Arrange: Only one section has content
    PageContent::create([
        'key' => 'about_section_2',
        'content' => 'Only middle section has content',
        'title' => 'About Section 2',
        'page' => 'about',
    ]);

    // Act & Assert
    Livewire::test(AboutSection::class)
        ->assertSee('Only middle section has content')
        ->assertViewHas('aboutContent.about_section_1', '')
        ->assertViewHas('aboutContent.about_section_2', 'Only middle section has content')
        ->assertViewHas('aboutContent.about_section_3', '');
});

test('about section component renders the correct view', function () {
    // Act & Assert
    Livewire::test(AboutSection::class)
        ->assertViewIs('livewire.components.about-section');
});
