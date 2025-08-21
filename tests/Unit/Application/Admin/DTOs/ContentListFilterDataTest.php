<?php

declare(strict_types=1);

use App\Application\Admin\DTOs\ContentListFilterData;

it('can create ContentListFilterData with default values', function () {
    $filter = new ContentListFilterData();

    expect($filter->page)->toBe('all');
    expect($filter->search)->toBe('');
    expect($filter->perPage)->toBe(15);
});

it('can create ContentListFilterData with custom values', function () {
    $filter = new ContentListFilterData(
        page: 'home',
        search: 'welcome',
        perPage: 10
    );

    expect($filter->page)->toBe('home');
    expect($filter->search)->toBe('welcome');
    expect($filter->perPage)->toBe(10);
});

it('can create ContentListFilterData with partial values', function () {
    $filter = new ContentListFilterData(page: 'about', search: 'company');

    expect($filter->page)->toBe('about');
    expect($filter->search)->toBe('company');
    expect($filter->perPage)->toBe(15); // Default value
});

it('can create ContentListFilterData from array', function () {
    $data = [
        'page' => 'contact',
        'search' => 'phone',
        'perPage' => 20
    ];

    $filter = ContentListFilterData::fromArray($data);

    expect($filter->page)->toBe('contact');
    expect($filter->search)->toBe('phone');
    expect($filter->perPage)->toBe(20);
});

it('can create ContentListFilterData from array with missing values', function () {
    $data = [
        'page' => 'home'
    ];

    $filter = ContentListFilterData::fromArray($data);

    expect($filter->page)->toBe('home');
    expect($filter->search)->toBe(''); // Default
    expect($filter->perPage)->toBe(15); // Default
});

it('can create ContentListFilterData from empty array', function () {
    $filter = ContentListFilterData::fromArray([]);

    expect($filter->page)->toBe('all'); // Default
    expect($filter->search)->toBe(''); // Default
    expect($filter->perPage)->toBe(15); // Default
});

it('can convert ContentListFilterData to array', function () {
    $filter = new ContentListFilterData(
        page: 'about',
        search: 'story',
        perPage: 25
    );

    $array = $filter->toArray();

    expect($array)->toBe([
        'page' => 'about',
        'search' => 'story',
        'perPage' => 25
    ]);
});

it('can check if filter has search term', function () {
    $filterWithSearch = new ContentListFilterData(search: 'test');
    $filterWithoutSearch = new ContentListFilterData();

    expect($filterWithSearch->hasSearch())->toBeTrue();
    expect($filterWithoutSearch->hasSearch())->toBeFalse();
});

it('can check if filter is for all pages', function () {
    $allPagesFilter = new ContentListFilterData(); // Default 'all'
    $specificPageFilter = new ContentListFilterData(page: 'home');

    expect($allPagesFilter->isAllPages())->toBeTrue();
    expect($specificPageFilter->isAllPages())->toBeFalse();
});

it('can check if filter is for specific page', function () {
    $homeFilter = new ContentListFilterData(page: 'home');
    $aboutFilter = new ContentListFilterData(page: 'about');

    expect($homeFilter->isForPage('home'))->toBeTrue();
    expect($homeFilter->isForPage('about'))->toBeFalse();
    expect($aboutFilter->isForPage('about'))->toBeTrue();
});