<?php

declare(strict_types=1);

use App\Domain\Admin\Enums\ContentKeys;
use App\Livewire\Admin\ContentEdit;
use App\Models\PageContent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

it('can render content edit component with existing content', function () {
    $content = PageContent::factory()->create([
        'key' => 'home_hero',
        'title' => 'Home Hero',
        'content' => 'Welcome to our site',
        'page' => 'home'
    ]);

    Livewire::test(ContentEdit::class, ['contentId' => $content->id])
        ->assertSuccessful()
        ->assertSet('content', 'Welcome to our site')
        ->assertSet('title', 'Home Hero')
        ->assertSet('key', 'home_hero')
        ->assertSet('page', 'home');
});

it('can render content edit component for new content', function () {
    Livewire::test(ContentEdit::class, ['contentId' => null])
        ->assertSuccessful()
        ->assertSet('content', '')
        ->assertSet('title', '')
        ->assertSet('key', '')
        ->assertSet('page', 'home'); // Default page
});

it('can save existing content', function () {
    $pageContent = PageContent::factory()->create([
        'key' => 'home_hero',
        'title' => 'Old Title',
        'content' => 'Old content',
        'page' => 'home'
    ]);

    Livewire::test(ContentEdit::class, ['contentId' => $pageContent->id])
        ->set('content', 'Updated content')
        ->set('title', 'Updated Title')
        ->call('save')
        ->assertDispatched('content-saved');

    $pageContent->refresh();
    expect($pageContent->content)->toBe('Updated content');
    expect($pageContent->title)->toBe('Updated Title');
});

it('can create new content', function () {
    expect(PageContent::where('key', 'home_hero')->exists())->toBeFalse();

    // Test the creation by verifying the database directly
    $component = Livewire::test(ContentEdit::class, ['contentId' => null]);
    $component->set('content', 'New content');
    $component->set('title', 'New Title'); 
    $component->set('page', 'home');
    $component->set('key', 'home_hero');
    $component->call('save');

    // Verify the content was actually created in database
    $content = PageContent::where('key', 'home_hero')->first();
    expect($content)->not->toBeNull();
    expect($content->title)->toBe('New Title');
    expect($content->content)->toBe('New content');
    expect($content->page)->toBe('home');

    // If content was created, the event should have been dispatched
    $component->assertDispatched('content-saved');
});

it('validates required fields when saving', function () {
    Livewire::test(ContentEdit::class, ['contentId' => null])
        ->set('content', '')
        ->set('key', '')
        ->set('page', '')
        ->call('save')
        ->assertHasErrors(['content', 'key', 'page']);
});

it('validates key uniqueness for new content', function () {
    PageContent::factory()->create(['key' => 'home_hero', 'page' => 'home']);

    Livewire::test(ContentEdit::class, ['contentId' => null])
        ->set('content', 'Some content')
        ->set('key', 'home_hero')
        ->set('page', 'home')
        ->call('save')
        ->assertHasErrors(['key']);
});

it('allows updating existing content without key uniqueness error', function () {
    $content = PageContent::factory()->create([
        'key' => 'home_hero',
        'page' => 'home',
        'content' => 'Old content'
    ]);

    Livewire::test(ContentEdit::class, ['contentId' => $content->id])
        ->set('content', 'Updated content')
        ->call('save')
        ->assertHasNoErrors();
});

it('validates key is valid for selected page', function () {
    Livewire::test(ContentEdit::class, ['contentId' => null])
        ->set('content', 'Some content')
        ->set('key', 'home_hero')
        ->set('page', 'about')
        ->call('save')
        ->assertHasErrors(['key']);
});

it('updates available keys when page changes', function () {
    $component = Livewire::test(ContentEdit::class, ['contentId' => null])
        ->set('page', 'home');

    $availableKeys = $component->get('availableKeys');
    expect($availableKeys)->toContain('home_hero');
    expect($availableKeys)->not->toContain('about_hero');

    $component->set('page', 'about');
    $availableKeys = $component->get('availableKeys');
    expect($availableKeys)->toContain('about_hero');
    expect($availableKeys)->not->toContain('home_hero');
});

it('provides available pages from ContentKeys enum', function () {
    $component = Livewire::test(ContentEdit::class, ['contentId' => null]);
    $availablePages = $component->get('availablePages');

    expect($availablePages)->toBe(['home', 'about', 'contact']);
});

it('can cancel editing and reset form', function () {
    $content = PageContent::factory()->create([
        'content' => 'Original content',
        'title' => 'Original title'
    ]);

    Livewire::test(ContentEdit::class, ['contentId' => $content->id])
        ->set('content', 'Modified content')
        ->set('title', 'Modified title')
        ->call('cancel')
        ->assertSet('content', 'Original content')
        ->assertSet('title', 'Original title');
});

it('can delete existing content', function () {
    $content = PageContent::factory()->create();

    Livewire::test(ContentEdit::class, ['contentId' => $content->id])
        ->call('delete')
        ->assertDispatched('content-deleted');

    expect(PageContent::find($content->id))->toBeNull();
});

it('cannot delete non-existing content', function () {
    Livewire::test(ContentEdit::class, ['contentId' => null])
        ->call('delete')
        ->assertHasNoErrors();
});
