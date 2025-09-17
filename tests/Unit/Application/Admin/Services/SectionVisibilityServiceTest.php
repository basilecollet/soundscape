<?php

use App\Application\Admin\Services\SectionVisibilityService;
use App\Domain\Admin\Repositories\SectionSettingRepository;
use App\Models\SectionSetting;
use Mockery\Expectation;
use Mockery\MockInterface;

test('isSectionEnabled returns true for non-disableable sections', function () {
    /** @var SectionSettingRepository&MockInterface $repository */
    $repository = Mockery::mock(SectionSettingRepository::class);
    $service = new SectionVisibilityService($repository);

    // Hero sections are not disableable, should always return true
    expect($service->isSectionEnabled('hero', 'home'))->toBeTrue()
        ->and($service->isSectionEnabled('hero', 'about'))->toBeTrue()
        ->and($service->isSectionEnabled('form', 'contact'))->toBeTrue();
});

test('isSectionEnabled delegates to repository for disableable sections', function () {
    /** @var SectionSettingRepository&MockInterface $repository */
    $repository = Mockery::mock(SectionSettingRepository::class);

    /** @var Expectation $expectation */
    $expectation= $repository->shouldReceive('isSectionEnabled');
    $expectation->once()
        ->with('features', 'home')
        ->andReturn(true);

    $service = new SectionVisibilityService($repository);

    expect($service->isSectionEnabled('features', 'home'))->toBeTrue();
});

test('setSectionEnabled returns false for non-disableable sections', function () {
    /** @var SectionSettingRepository&MockInterface $repository */
    $repository = Mockery::mock(SectionSettingRepository::class);
    $service = new SectionVisibilityService($repository);

    // Should not be able to disable hero sections
    expect($service->setSectionEnabled('hero', 'home', false))->toBeFalse()
        ->and($service->setSectionEnabled('form', 'contact', false))->toBeFalse();
});

test('setSectionEnabled delegates to repository for disableable sections', function () {
    /** @var SectionSettingRepository&MockInterface $repository */
    $repository = Mockery::mock(SectionSettingRepository::class);

    /** @var Expectation $expectation */
    $expectation = $repository->shouldReceive('setSectionEnabled');
    $expectation->once()
        ->with('features', 'home', false)
        ->andReturn(new SectionSetting(['section_key' => 'features', 'page' => 'home', 'is_enabled' => false]));

    $service = new SectionVisibilityService($repository);

    expect($service->setSectionEnabled('features', 'home', false))->toBeTrue();
});

test('getEnabledSectionsForPage returns both disableable and non-disableable sections', function () {
    /** @var SectionSettingRepository&MockInterface $repository */
    $repository = Mockery::mock(SectionSettingRepository::class);

    /** @var Expectation $expectation */
    $expectation = $repository->shouldReceive('getEnabledSectionsForPage');
    $expectation->once()
        ->with('home')
        ->andReturn(['features', 'cta']);

    $service = new SectionVisibilityService($repository);

    $enabledSections = $service->getEnabledSectionsForPage('home');

    expect($enabledSections)->toContain('features')
        ->toContain('cta')
        ->toContain('hero'); // Non-disableable section should also be included
});

test('getNonDisableableSectionsForPage returns correct sections for each page', function () {
    /** @var SectionSettingRepository&MockInterface $repository */
    $repository = Mockery::mock(SectionSettingRepository::class);
    $service = new SectionVisibilityService($repository);

    expect($service->getNonDisableableSectionsForPage('home'))->toEqual(['hero'])
        ->and($service->getNonDisableableSectionsForPage('about'))->toEqual(['hero', 'bio'])
        ->and($service->getNonDisableableSectionsForPage('contact'))->toEqual(['hero', 'form', 'info'])
        ->and($service->getNonDisableableSectionsForPage('unknown'))->toEqual([]);
});
