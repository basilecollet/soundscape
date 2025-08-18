<?php

use App\Livewire\Components\Footer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('footer component renders successfully', function () {
    // Act & Assert
    Livewire::test(Footer::class)
        ->assertStatus(200);
});

test('footer component renders the correct view', function () {
    // Act & Assert
    Livewire::test(Footer::class)
        ->assertViewIs('livewire.components.footer');
});

test('footer displays copyright with current year', function () {
    $currentYear = date('Y');

    // Act & Assert
    Livewire::test(Footer::class)
        ->assertSee($currentYear)
        ->assertSee('soundscapeaudio.store');
});

test('footer contains essential links', function () {
    // Act & Assert
    Livewire::test(Footer::class)
        ->assertSee('Mailing list')
        ->assertSee('Instagram')
        ->assertSee('Legal');
});
