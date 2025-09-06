<?php

use App\Application\Admin\Services\SectionVisibilityService;
use App\Domain\Admin\Repositories\SectionSettingRepository;
use App\Models\SectionSetting;

test('isSectionEnabled returns true for non-disableable sections', function () {
    $repository = Mockery::mock(SectionSettingRepository::class);
    $service = new SectionVisibilityService($repository);

    // Hero sections are not disableable, should always return true
    expect($service->isSectionEnabled('hero', 'home'))->toBeTrue();
    expect($service->isSectionEnabled('hero', 'about'))->toBeTrue();
    expect($service->isSectionEnabled('form', 'contact'))->toBeTrue();
});

test('isSectionEnabled delegates to repository for disableable sections', function () {
    $repository = Mockery::mock(SectionSettingRepository::class);
    $repository->shouldReceive('isSectionEnabled')
        ->with('features', 'home')
        ->once()
        ->andReturn(true);

    $service = new SectionVisibilityService($repository);

    expect($service->isSectionEnabled('features', 'home'))->toBeTrue();
});

test('setSectionEnabled returns false for non-disableable sections', function () {
    $repository = Mockery::mock(SectionSettingRepository::class);
    $service = new SectionVisibilityService($repository);

    // Should not be able to disable hero sections
    expect($service->setSectionEnabled('hero', 'home', false))->toBeFalse();
    expect($service->setSectionEnabled('form', 'contact', false))->toBeFalse();
});

test('setSectionEnabled delegates to repository for disableable sections', function () {
    $repository = Mockery::mock(SectionSettingRepository::class);
    $repository->shouldReceive('setSectionEnabled')
        ->with('features', 'home', false)
        ->once()
        ->andReturn(new SectionSetting(['section_key' => 'features', 'page' => 'home', 'is_enabled' => false]));

    $service = new SectionVisibilityService($repository);

    expect($service->setSectionEnabled('features', 'home', false))->toBeTrue();
});

test('getEnabledSectionsForPage returns both disableable and non-disableable sections', function () {
    $repository = Mockery::mock(SectionSettingRepository::class);
    $repository->shouldReceive('getEnabledSectionsForPage')
        ->with('home')
        ->once()
        ->andReturn(['features', 'cta']);

    $service = new SectionVisibilityService($repository);

    $enabledSections = $service->getEnabledSectionsForPage('home');

    expect($enabledSections)->toContain('features')
        ->toContain('cta')
        ->toContain('hero'); // Non-disableable section should also be included
});

test('getNonDisableableSectionsForPage returns correct sections for each page', function () {
    $repository = Mockery::mock(SectionSettingRepository::class);
    $service = new SectionVisibilityService($repository);

    expect($service->getNonDisableableSectionsForPage('home'))->toEqual(['hero']);
    expect($service->getNonDisableableSectionsForPage('about'))->toEqual(['hero', 'bio']);
    expect($service->getNonDisableableSectionsForPage('contact'))->toEqual(['hero', 'form', 'info']);
    expect($service->getNonDisableableSectionsForPage('unknown'))->toEqual([]);
});
