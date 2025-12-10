<?php

declare(strict_types=1);

use App\Domain\Portfolio\Entities\HomePage;
use App\Domain\Portfolio\Services\SectionVisibilityServiceInterface;
use App\Domain\Portfolio\ValueObjects\PageField;
use Mockery\Expectation;
use Mockery\MockInterface;

test('home page has minimum content with only hero section', function () {
    // Given: Hero fields exist
    $fields = [
        PageField::fromKeyAndContent('home_hero_title', 'Title'),
        PageField::fromKeyAndContent('home_hero_subtitle', 'Subtitle'),
        PageField::fromKeyAndContent('home_hero_text', 'Text'),
    ];

    // And: Features section disabled
    /** @var SectionVisibilityServiceInterface&MockInterface $sectionService */
    $sectionService = Mockery::mock(SectionVisibilityServiceInterface::class);

    /** @var Expectation $expectation */
    $expectation = $sectionService->shouldReceive('isSectionEnabled');
    $expectation->with('features', 'home')
        ->andReturn(false);

    $page = HomePage::reconstitute($fields, $sectionService);

    // When/Then
    expect($page->hasMinimumContent())->toBeTrue()
        ->and($page->getMissingFields())->toBeEmpty()
        ->and($page->shouldShowFeatures())->toBeFalse();
});

test('home page missing hero field', function () {
    // Given: Only 2 hero fields
    $fields = [
        PageField::fromKeyAndContent('home_hero_title', 'Title'),
        PageField::fromKeyAndContent('home_hero_subtitle', 'Subtitle'),
        // home_hero_text is missing
    ];

    /** @var SectionVisibilityServiceInterface&MockInterface $sectionService */
    $sectionService = Mockery::mock(SectionVisibilityServiceInterface::class);

    /** @var Expectation $expectation */
    $expectation = $sectionService->shouldReceive('isSectionEnabled');
    $expectation->with('features', 'home')
        ->andReturn(false);

    $page = HomePage::reconstitute($fields, $sectionService);

    // When/Then
    expect($page->hasMinimumContent())->toBeFalse()
        ->and($page->getMissingFields())->toContain('home_hero_text')
        ->and($page->getMissingFields())->toHaveCount(1);
});

test('home page has minimum content with features enabled and complete', function () {
    // Given: All fields exist
    $fields = [
        PageField::fromKeyAndContent('home_hero_title', 'Title'),
        PageField::fromKeyAndContent('home_hero_subtitle', 'Subtitle'),
        PageField::fromKeyAndContent('home_hero_text', 'Text'),
        PageField::fromKeyAndContent('home_feature_1_title', 'F1 Title'),
        PageField::fromKeyAndContent('home_feature_1_description', 'F1 Desc'),
        PageField::fromKeyAndContent('home_feature_2_title', 'F2 Title'),
        PageField::fromKeyAndContent('home_feature_2_description', 'F2 Desc'),
        PageField::fromKeyAndContent('home_feature_3_title', 'F3 Title'),
        PageField::fromKeyAndContent('home_feature_3_description', 'F3 Desc'),
    ];

    /** @var SectionVisibilityServiceInterface&MockInterface $sectionService */
    $sectionService = Mockery::mock(SectionVisibilityServiceInterface::class);

    /** @var Expectation $expectation */
    $expectation = $sectionService->shouldReceive('isSectionEnabled');
    $expectation->with('features', 'home')
        ->andReturn(true);

    $page = HomePage::reconstitute($fields, $sectionService);

    // When/Then
    expect($page->hasMinimumContent())->toBeTrue()
        ->and($page->getMissingFields())->toBeEmpty()
        ->and($page->shouldShowFeatures())->toBeTrue();
});

test('home page missing features field when section enabled', function () {
    // Given: Hero complete but features incomplete
    $fields = [
        PageField::fromKeyAndContent('home_hero_title', 'Title'),
        PageField::fromKeyAndContent('home_hero_subtitle', 'Subtitle'),
        PageField::fromKeyAndContent('home_hero_text', 'Text'),
        PageField::fromKeyAndContent('home_feature_1_title', 'F1 Title'),
        // Missing other features
    ];

    /** @var SectionVisibilityServiceInterface&MockInterface $sectionService */
    $sectionService = Mockery::mock(SectionVisibilityServiceInterface::class);

    /** @var Expectation $expectation */
    $expectation = $sectionService->shouldReceive('isSectionEnabled');
    $expectation->with('features', 'home')
        ->andReturn(true);

    $page = HomePage::reconstitute($fields, $sectionService);

    // When/Then
    expect($page->hasMinimumContent())->toBeFalse()
        ->and($page->getMissingFields())->toHaveCount(5);
});

test('home page identifies empty fields as missing', function () {
    // Given: Fields exist but are empty
    $fields = [
        PageField::fromKeyAndContent('home_hero_title', ''),
        PageField::fromKeyAndContent('home_hero_subtitle', null),
        PageField::fromKeyAndContent('home_hero_text', '   '),
    ];

    /** @var SectionVisibilityServiceInterface&MockInterface $sectionService */
    $sectionService = Mockery::mock(SectionVisibilityServiceInterface::class);

    /** @var Expectation $expectation */
    $expectation = $sectionService->shouldReceive('isSectionEnabled');
    $expectation->with('features', 'home')
        ->andReturn(false);

    $page = HomePage::reconstitute($fields, $sectionService);

    // When/Then
    expect($page->hasMinimumContent())->toBeFalse()
        ->and($page->getMissingFields())->toHaveCount(3);
});

test('home page should show cta when enabled', function () {
    $fields = [
        PageField::fromKeyAndContent('home_hero_title', 'Title'),
        PageField::fromKeyAndContent('home_hero_subtitle', 'Subtitle'),
        PageField::fromKeyAndContent('home_hero_text', 'Text'),
    ];

    /** @var SectionVisibilityServiceInterface&MockInterface $sectionService */
    $sectionService = Mockery::mock(SectionVisibilityServiceInterface::class);

    /** @var Expectation $expectation1 */
    $expectation1 = $sectionService->shouldReceive('isSectionEnabled');
    $expectation1->with('features', 'home')
        ->andReturn(false);

    /** @var Expectation $expectation2 */
    $expectation2 = $sectionService->shouldReceive('isSectionEnabled');
    $expectation2->with('cta', 'home')
        ->andReturn(true);

    $page = HomePage::reconstitute($fields, $sectionService);

    expect($page->shouldShowCta())->toBeTrue();
});
