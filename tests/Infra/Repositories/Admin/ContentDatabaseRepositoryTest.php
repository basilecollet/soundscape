<?php

declare(strict_types=1);

namespace Tests\Infra\Repositories\Admin;

use App\Infra\Repositories\Admin\ContentDatabaseRepository;
use App\Models\PageContent;
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
        ->toThrow(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
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
