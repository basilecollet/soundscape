<?php

use App\Livewire\Components\Navbar;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('navbar component renders successfully', function () {
    // Act & Assert
    Livewire::test(Navbar::class)
        ->assertStatus(200);
});

test('navbar component renders the correct view', function () {
    // Act & Assert
    Livewire::test(Navbar::class)
        ->assertViewIs('livewire.components.navbar');
});

test('navbar shows navigation links', function () {
    // Act & Assert
    Livewire::test(Navbar::class)
        ->assertSee('Home')
        ->assertSee('About')
        ->assertSee('Contact');
});

test('navbar contains brand name', function () {
    // Act & Assert - The navbar uses config('app.name') which is 'Soundscape'
    Livewire::test(Navbar::class)
        ->assertSee('Soundscape');
});

test('navbar has dynamic styling based on scroll', function () {
    // Act & Assert - Check Alpine.js data attributes
    Livewire::test(Navbar::class)
        ->assertSeeHtml('x-data="{ scrolled: false, mobileMenuOpen: false }"')
        ->assertSeeHtml('x-init="window.addEventListener')
        ->assertStatus(200);
});
