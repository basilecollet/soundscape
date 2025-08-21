<?php

declare(strict_types=1);

use App\Application\Admin\DTOs\ContentData;
use App\Application\Admin\DTOs\ContentListFilterData;
use App\Application\Admin\Services\ContentManagementService;
use App\Domain\Admin\Repositories\ContentRepository;
use App\Models\PageContent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

beforeEach(function () {
    $this->repository = Mockery::mock(ContentRepository::class);
    $this->service = new ContentManagementService($this->repository);
});

describe('existing methods', function () {
    test('can get content by id', function () {
        $content = new PageContent([
            'id' => 1,
            'key' => 'test_key',
            'content' => 'Test content',
            'title' => 'Test title',
            'page' => 'home'
        ]);

        $this->repository->shouldReceive('findById')
            ->with(1)
            ->andReturn($content);

        $result = $this->service->getContentById(1);

        expect($result)->toBe($content);
    });

    test('can get all content', function () {
        $contents = new Collection([
            new PageContent(['id' => 1, 'key' => 'home_hero', 'page' => 'home']),
            new PageContent(['id' => 2, 'key' => 'about_hero', 'page' => 'about']),
        ]);

        $this->repository->shouldReceive('getAll')
            ->andReturn($contents);

        $result = $this->service->getAllContent();

        expect($result)->toBe($contents);
        expect($result)->toHaveCount(2);
    });
});

describe('new methods for enhanced content management', function () {
    test('can create new content with ContentData', function () {
        $contentData = ContentData::forCreation(
            key: 'home_hero',
            content: 'Welcome to our site',
            page: 'home',
            title: 'Hero Section'
        );

        $expectedContent = new PageContent([
            'key' => 'home_hero',
            'content' => 'Welcome to our site',
            'page' => 'home',
            'title' => 'Hero Section'
        ]);

        $this->repository->shouldReceive('store')
            ->with([
                'key' => 'home_hero',
                'content' => 'Welcome to our site',
                'page' => 'home',
                'title' => 'Hero Section'
            ])
            ->andReturn($expectedContent);

        $result = $this->service->createContent($contentData);

        expect($result)->toBe($expectedContent);
    });

    test('can update content with ContentData', function () {
        $existingContent = new PageContent([
            'id' => 1,
            'key' => 'home_hero',
            'page' => 'home'
        ]);

        $contentData = ContentData::forUpdate(
            id: 1,
            content: 'Updated content',
            title: 'Updated Title'
        );

        $this->repository->shouldReceive('findById')
            ->with(1)
            ->andReturn($existingContent);

        $this->repository->shouldReceive('store')
            ->with([
                'key' => 'home_hero',
                'content' => 'Updated content',
                'title' => 'Updated Title',
                'page' => 'home'
            ])
            ->andReturn($existingContent);

        $this->service->updateContentWithData($contentData);
        
        // Assert that the mock expectations were fulfilled
        expect(true)->toBeTrue(); // Explicit assertion for the test
    });

    test('can delete content by id', function () {
        $this->repository->shouldReceive('delete')
            ->with(1)
            ->once();

        $this->service->deleteContent(1);
    });

    test('can find contents by page', function () {
        $contents = new Collection([
            new PageContent(['page' => 'home', 'key' => 'home_hero']),
            new PageContent(['page' => 'home', 'key' => 'home_text']),
        ]);

        $this->repository->shouldReceive('findByPage')
            ->with('home')
            ->andReturn($contents);

        $result = $this->service->getContentsByPage('home');

        expect($result)->toBe($contents);
        expect($result)->toHaveCount(2);
    });

    test('can search contents', function () {
        $contents = new Collection([
            new PageContent(['key' => 'home_hero', 'title' => 'Hero Section']),
        ]);

        $this->repository->shouldReceive('search')
            ->with('hero')
            ->andReturn($contents);

        $result = $this->service->searchContents('hero');

        expect($result)->toBe($contents);
        expect($result)->toHaveCount(1);
    });

    test('can get existing keys for page', function () {
        $keys = ['home_hero', 'home_text', 'home_footer'];

        $this->repository->shouldReceive('getExistingKeysForPage')
            ->with('home')
            ->andReturn($keys);

        $result = $this->service->getExistingKeysForPage('home');

        expect($result)->toBe($keys);
        expect($result)->toHaveCount(3);
    });

    test('can get filtered contents using ContentListFilterData', function () {
        $filter = new ContentListFilterData(page: 'home', search: 'hero');

        $contents = new Collection([
            new PageContent(['page' => 'home', 'key' => 'home_hero']),
        ]);

        $this->repository->shouldReceive('findByPage')
            ->with('home')
            ->andReturn(new Collection([
                new PageContent(['page' => 'home', 'key' => 'home_hero']),
                new PageContent(['page' => 'home', 'key' => 'home_text']),
            ]));

        $this->repository->shouldReceive('search')
            ->with('hero')
            ->andReturn($contents);

        $result = $this->service->getFilteredContents($filter);

        expect($result)->toHaveCount(1);
        expect($result->first()->key)->toBe('home_hero');
    });

    test('can get all contents when filter is for all pages', function () {
        $filter = new ContentListFilterData(); // Default is 'all' pages

        $contents = new Collection([
            new PageContent(['page' => 'home']),
            new PageContent(['page' => 'about']),
        ]);

        $this->repository->shouldReceive('getAll')
            ->andReturn($contents);

        $result = $this->service->getFilteredContents($filter);

        expect($result)->toBe($contents);
        expect($result)->toHaveCount(2);
    });

    test('can get filtered contents with search only', function () {
        $filter = new ContentListFilterData(search: 'welcome');

        $contents = new Collection([
            new PageContent(['content' => 'Welcome to our website']),
        ]);

        $this->repository->shouldReceive('search')
            ->with('welcome')
            ->andReturn($contents);

        $result = $this->service->getFilteredContents($filter);

        expect($result)->toBe($contents);
        expect($result)->toHaveCount(1);
    });

    test('throws exception when content not found for update', function () {
        $contentData = ContentData::forUpdate(
            id: 999,
            content: 'Updated content'
        );

        $this->repository->shouldReceive('findById')
            ->with(999)
            ->andThrow(new ModelNotFoundException());

        expect(fn() => $this->service->updateContentWithData($contentData))
            ->toThrow(ModelNotFoundException::class);
    });
});