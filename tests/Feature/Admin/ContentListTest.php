<?php

declare(strict_types=1);

use App\Domain\Admin\Enums\ContentKeys;
use App\Livewire\Admin\ContentList;
use App\Models\PageContent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

it('can render content list component', function () {
    Livewire::test(ContentList::class)
        ->assertSuccessful();
});

it('displays all contents by default', function () {
    PageContent::factory()->create(['page' => 'home', 'key' => 'home_text']);
    PageContent::factory()->create(['page' => 'about', 'key' => 'about_section_1']);
    PageContent::factory()->create(['page' => 'contact', 'key' => 'contact_text']);

    Livewire::test(ContentList::class)
        ->assertSee('home_text')
        ->assertSee('about_section_1')
        ->assertSee('contact_text');
});

it('can filter contents by page', function () {
    PageContent::factory()->create(['page' => 'home', 'key' => 'home_text']);
    PageContent::factory()->create(['page' => 'about', 'key' => 'about_section_1']);

    Livewire::test(ContentList::class)
        ->set('selectedPage', 'home')
        ->assertSee('home_text')
        ->assertDontSee('about_section_1');
});

it('can search contents by key, title and content', function () {
    PageContent::factory()->create([
        'key' => 'home_text',
        'title' => 'Welcome Message',
        'content' => 'Welcome to our website',
    ]);
    PageContent::factory()->create([
        'key' => 'about_section_1',
        'title' => 'About Us',
        'content' => 'Learn about our company',
    ]);

    $component = Livewire::test(ContentList::class)
        ->set('search', 'Welcome');

    $contents = $component->get('contents');
    expect($contents)->toHaveCount(1);
    expect($contents->first()->key)->toBe('home_text');
});

it('displays missing keys for each page', function () {
    // Only create one content for home page, others should be missing
    PageContent::factory()->create(['page' => 'home', 'key' => 'home_text']);

    $component = Livewire::test(ContentList::class);
    $missingKeys = $component->get('missingKeys');

    // Home page should have missing keys (all except home_text)
    expect($missingKeys)->toHaveKey('home');
    expect($missingKeys['home'])->toContain('home_hero');
    expect($missingKeys['home'])->not->toContain('home_text');

    // About and contact pages should have all their keys missing
    expect($missingKeys)->toHaveKey('about');
    expect($missingKeys)->toHaveKey('contact');
});

it('can create missing content', function () {
    expect(PageContent::where('page', 'home')->where('key', 'home_hero')->exists())->toBeFalse();

    Livewire::test(ContentList::class)
        ->call('createMissingContent', 'home', 'home_hero')
        ->assertDispatched('content-created', key: 'home_hero');

    expect(PageContent::where([
        'page' => 'home',
        'key' => 'home_hero',
        'title' => ContentKeys::getLabel('home_hero'),
        'content' => '',
    ])->exists())->toBeTrue();
});

it('resets pagination when page filter changes', function () {
    PageContent::factory()->count(20)->create(); // Create enough content for pagination

    $component = Livewire::test(ContentList::class)
        ->set('selectedPage', 'home');

    // The pagination should be reset when changing the filter
    expect($component->instance()->getPage())->toBe(1);
});

it('resets pagination when search changes', function () {
    PageContent::factory()->count(20)->create(); // Create enough content for pagination

    $component = Livewire::test(ContentList::class)
        ->set('search', 'test');

    // The pagination should be reset when changing the search
    expect($component->instance()->getPage())->toBe(1);
});

it('orders contents by page then key', function () {
    // Create contents in mixed order
    PageContent::factory()->create(['page' => 'contact', 'key' => 'contact_text']);
    PageContent::factory()->create(['page' => 'about', 'key' => 'about_section_2']);
    PageContent::factory()->create(['page' => 'home', 'key' => 'home_text']);
    PageContent::factory()->create(['page' => 'about', 'key' => 'about_section_1']);

    $component = Livewire::test(ContentList::class);
    $contents = $component->get('contents');

    // Should be ordered: about_section_1, about_section_2, contact_text, home_text
    expect($contents->first()->key)->toBe('about_section_1');
    expect($contents->last()->key)->toBe('home_text');
});

it('provides available pages from ContentKeys enum', function () {
    $component = Livewire::test(ContentList::class);
    $availablePages = $component->get('availablePages');

    expect($availablePages)->toBe(['home', 'about', 'contact']);
});
