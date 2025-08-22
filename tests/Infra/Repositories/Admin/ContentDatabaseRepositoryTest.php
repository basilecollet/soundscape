<?php

declare(strict_types=1);

namespace Tests\Infra\Repositories\Admin;

use App\Infra\Repositories\Admin\ContentDatabaseRepository;
use App\Models\PageContent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->repository = new ContentDatabaseRepository;
});

test('can find content by id', function () {
    $content = PageContent::factory()->create([
        'key' => 'test_key',
        'content' => 'Test content',
        'title' => 'Test title',
        'page' => 'test',
    ]);

    $found = $this->repository->findById($content->id);

    expect($found->id)->toBe($content->id);
    expect($found->key)->toBe('test_key');
    expect($found->content)->toBe('Test content');
});

test('throws exception when content not found', function () {
    expect(fn () => $this->repository->findById(999))
        ->toThrow(ModelNotFoundException::class);
});

test('can get all content ordered by page and key', function () {
    PageContent::factory()->create(['page' => 'home', 'key' => 'z_key']);
    PageContent::factory()->create(['page' => 'about', 'key' => 'a_key']);
    PageContent::factory()->create(['page' => 'home', 'key' => 'b_key']);

    $contents = $this->repository->getAll();

    expect($contents)->toHaveCount(3);
    // Vérifier l'ordre : about/a_key, home/b_key, home/z_key
    expect($contents->first()->page)->toBe('about');
    expect($contents->last()->key)->toBe('z_key');
});

test('can store new content', function () {
    $data = [
        'key' => 'new_key',
        'content' => 'New content',
        'title' => 'New title',
        'page' => 'new_page',
    ];

    $content = $this->repository->store($data);

    expect($content->key)->toBe('new_key');
    expect($content->content)->toBe('New content');
    expect($content->title)->toBe('New title');
    expect($content->page)->toBe('new_page');

    $this->assertDatabaseHas('page_contents', $data);
});

test('can update existing content by key', function () {
    $existing = PageContent::factory()->create([
        'key' => 'existing_key',
        'content' => 'Old content',
        'title' => 'Old title',
    ]);

    $data = [
        'key' => 'existing_key',
        'content' => 'Updated content',
        'title' => 'Updated title',
        'page' => 'updated_page',
    ];

    $content = $this->repository->store($data);

    expect($content->id)->toBe($existing->id); // Même ID = mise à jour
    expect($content->content)->toBe('Updated content');
    expect($content->title)->toBe('Updated title');

    // Vérifier qu'il n'y a toujours qu'un seul enregistrement avec cette clé
    expect(PageContent::where('key', 'existing_key')->count())->toBe(1);
});

describe('delete method', function () {
    test('can delete existing content by id', function () {
        $content = PageContent::factory()->create([
            'key' => 'home_hero',
            'content' => 'Test content',
            'page' => 'home',
        ]);

        expect(PageContent::find($content->id))->not->toBeNull();

        $this->repository->delete($content->id);

        expect(PageContent::find($content->id))->toBeNull();
    });

    test('throws exception when trying to delete non-existent content', function () {
        expect(fn () => $this->repository->delete(999))
            ->toThrow(ModelNotFoundException::class);
    });
});

describe('findByPage method', function () {
    test('can find all contents for a specific page', function () {
        PageContent::factory()->create(['page' => 'home', 'key' => 'home_hero']);
        PageContent::factory()->create(['page' => 'home', 'key' => 'home_text']);
        PageContent::factory()->create(['page' => 'about', 'key' => 'about_section_1']);

        $homeContents = $this->repository->findByPage('home');

        expect($homeContents)->toHaveCount(2);
        expect($homeContents->pluck('page')->unique()->toArray())->toBe(['home']);
        expect($homeContents->pluck('key')->toArray())->toContain('home_hero', 'home_text');
    });

    test('returns empty collection when no contents exist for page', function () {
        $contents = $this->repository->findByPage('nonexistent');

        expect($contents)->toHaveCount(0);
        expect($contents)->toBeInstanceOf(Collection::class);
    });

    test('orders contents by key within page', function () {
        PageContent::factory()->create(['page' => 'home', 'key' => 'home_text']);
        PageContent::factory()->create(['page' => 'home', 'key' => 'home_hero']);

        $contents = $this->repository->findByPage('home');

        // Should be ordered alphabetically by key
        expect($contents->first()->key)->toBe('home_hero');
        expect($contents->last()->key)->toBe('home_text');
    });
});

describe('search method', function () {
    test('can search contents by key', function () {
        PageContent::factory()->create(['key' => 'home_hero', 'title' => 'Hero', 'content' => 'Welcome']);
        PageContent::factory()->create(['key' => 'about_text', 'title' => 'About', 'content' => 'Company info']);

        $results = $this->repository->search('hero');

        expect($results)->toHaveCount(1);
        expect($results->first()->key)->toBe('home_hero');
    });

    test('can search contents by title', function () {
        PageContent::factory()->create(['key' => 'home_hero', 'title' => 'Hero Section', 'content' => 'Welcome']);
        PageContent::factory()->create(['key' => 'about_text', 'title' => 'About Us', 'content' => 'Company info']);

        $results = $this->repository->search('Hero');

        expect($results)->toHaveCount(1);
        expect($results->first()->key)->toBe('home_hero');
    });

    test('can search contents by content', function () {
        PageContent::factory()->create(['key' => 'home_hero', 'title' => 'Hero', 'content' => 'Welcome to our website']);
        PageContent::factory()->create(['key' => 'about_text', 'title' => 'About', 'content' => 'Company information']);

        $results = $this->repository->search('website');

        expect($results)->toHaveCount(1);
        expect($results->first()->key)->toBe('home_hero');
    });

    test('search is case insensitive', function () {
        PageContent::factory()->create(['key' => 'home_hero', 'title' => 'Hero Section', 'content' => 'Welcome']);

        $results = $this->repository->search('HERO');

        expect($results)->toHaveCount(1);
        expect($results->first()->key)->toBe('home_hero');
    });

    test('returns empty collection when no matches found', function () {
        PageContent::factory()->create(['key' => 'home_text', 'title' => 'Welcome', 'content' => 'Hello world']);

        $results = $this->repository->search('nonexistent');

        expect($results)->toHaveCount(0);
        expect($results)->toBeInstanceOf(Collection::class);
    });
});

describe('getExistingKeysForPage method', function () {
    test('returns existing keys for a specific page', function () {
        PageContent::factory()->create(['page' => 'home', 'key' => 'home_hero']);
        PageContent::factory()->create(['page' => 'home', 'key' => 'home_text']);
        PageContent::factory()->create(['page' => 'about', 'key' => 'about_section_1']);

        $homeKeys = $this->repository->getExistingKeysForPage('home');

        expect($homeKeys)->toHaveCount(2);
        expect($homeKeys)->toContain('home_hero', 'home_text');
        expect($homeKeys)->not->toContain('about_section_1');
    });

    test('returns empty array when no keys exist for page', function () {
        $keys = $this->repository->getExistingKeysForPage('nonexistent');

        expect($keys)->toHaveCount(0);
        expect($keys)->toBe([]);
    });
});

describe('count method', function () {
    test('can count total content items', function () {
        PageContent::factory()->count(5)->create();

        $count = $this->repository->count();

        expect($count)->toBe(5);
    });

    test('returns zero when no content exists', function () {
        $count = $this->repository->count();

        expect($count)->toBe(0);
    });
});

describe('findLatest method', function () {
    test('can find latest content items with limit', function () {
        // Create contents with different timestamps
        PageContent::factory()->create(['updated_at' => now()->subDays(3)]);
        PageContent::factory()->create(['updated_at' => now()->subDays(1)]);
        PageContent::factory()->create(['updated_at' => now()]);

        $latest = $this->repository->findLatest(2);

        expect($latest)->toHaveCount(2);
        // Should return newest first
        expect($latest->first()->updated_at->format('Y-m-d'))->toBe(now()->format('Y-m-d'));
    });

    test('returns empty collection when no content exists', function () {
        $contents = $this->repository->findLatest(5);

        expect($contents)->toHaveCount(0);
        expect($contents)->toBeInstanceOf(Collection::class);
    });
});
