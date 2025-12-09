<?php

declare(strict_types=1);

use App\Domain\Admin\Services\SectionVisibilityServiceInterface;
use App\Domain\Portfolio\Entities\AboutPage;
use App\Domain\Portfolio\ValueObjects\PageField;
use Mockery\Expectation;
use Mockery\MockInterface;

test('about page has minimum content with only hero and bio', function () {
    // Given: Hero + bio fields exist
    $fields = [
        PageField::fromKeyAndContent('about_title', 'About Me'),
        PageField::fromKeyAndContent('about_intro', 'Introduction'),
        PageField::fromKeyAndContent('about_bio', 'Biography'),
    ];

    // And: All optional sections are disabled
    /** @var SectionVisibilityServiceInterface&MockInterface $sectionService */
    $sectionService = Mockery::mock(SectionVisibilityServiceInterface::class);

    /** @var Expectation $expectation1 */
    $expectation1 = $sectionService->shouldReceive('isSectionEnabled');
    $expectation1->with('experience', 'about')->andReturn(false);

    /** @var Expectation $expectation2 */
    $expectation2 = $sectionService->shouldReceive('isSectionEnabled');
    $expectation2->with('services', 'about')->andReturn(false);

    /** @var Expectation $expectation3 */
    $expectation3 = $sectionService->shouldReceive('isSectionEnabled');
    $expectation3->with('philosophy', 'about')->andReturn(false);

    $page = AboutPage::reconstitute($fields, $sectionService);

    // When/Then
    expect($page->hasMinimumContent())->toBeTrue()
        ->and($page->getMissingFields())->toBeEmpty()
        ->and($page->shouldShowExperience())->toBeFalse()
        ->and($page->shouldShowServices())->toBeFalse()
        ->and($page->shouldShowPhilosophy())->toBeFalse();
});

test('about page missing hero field', function () {
    // Given: Only 2 hero fields exist
    $fields = [
        PageField::fromKeyAndContent('about_title', 'About Me'),
        PageField::fromKeyAndContent('about_intro', 'Introduction'),
        // about_bio is missing
    ];

    /** @var SectionVisibilityServiceInterface&MockInterface $sectionService */
    $sectionService = Mockery::mock(SectionVisibilityServiceInterface::class);

    /** @var Expectation $expectation1 */
    $expectation1 = $sectionService->shouldReceive('isSectionEnabled');
    $expectation1->with('experience', 'about')->andReturn(false);

    /** @var Expectation $expectation2 */
    $expectation2 = $sectionService->shouldReceive('isSectionEnabled');
    $expectation2->with('services', 'about')->andReturn(false);

    /** @var Expectation $expectation3 */
    $expectation3 = $sectionService->shouldReceive('isSectionEnabled');
    $expectation3->with('philosophy', 'about')->andReturn(false);

    $page = AboutPage::reconstitute($fields, $sectionService);

    // When/Then
    expect($page->hasMinimumContent())->toBeFalse()
        ->and($page->getMissingFields())->toContain('about_bio')
        ->and($page->getMissingFields())->toHaveCount(1);
});

test('about page has minimum content with complete experience section', function () {
    // Given: Hero + bio + experience fields exist
    $fields = [
        PageField::fromKeyAndContent('about_title', 'About Me'),
        PageField::fromKeyAndContent('about_intro', 'Introduction'),
        PageField::fromKeyAndContent('about_bio', 'Biography'),
        PageField::fromKeyAndContent('about_experience_years', '10+'),
        PageField::fromKeyAndContent('about_experience_projects', '50+'),
        PageField::fromKeyAndContent('about_experience_clients', '30+'),
    ];

    // And: Experience is enabled, others disabled
    /** @var SectionVisibilityServiceInterface&MockInterface $sectionService */
    $sectionService = Mockery::mock(SectionVisibilityServiceInterface::class);

    /** @var Expectation $expectation1 */
    $expectation1 = $sectionService->shouldReceive('isSectionEnabled');
    $expectation1->with('experience', 'about')->andReturn(true);

    /** @var Expectation $expectation2 */
    $expectation2 = $sectionService->shouldReceive('isSectionEnabled');
    $expectation2->with('services', 'about')->andReturn(false);

    /** @var Expectation $expectation3 */
    $expectation3 = $sectionService->shouldReceive('isSectionEnabled');
    $expectation3->with('philosophy', 'about')->andReturn(false);

    $page = AboutPage::reconstitute($fields, $sectionService);

    // When/Then
    expect($page->hasMinimumContent())->toBeTrue()
        ->and($page->getMissingFields())->toBeEmpty()
        ->and($page->shouldShowExperience())->toBeTrue();
});

test('about page does not have minimum content with incomplete services', function () {
    // Given: Hero + bio fields exist
    $fields = [
        PageField::fromKeyAndContent('about_title', 'About Me'),
        PageField::fromKeyAndContent('about_intro', 'Introduction'),
        PageField::fromKeyAndContent('about_bio', 'Biography'),
        // Only 5 out of 6 services
        PageField::fromKeyAndContent('about_service_1', 'Service 1'),
        PageField::fromKeyAndContent('about_service_2', 'Service 2'),
        PageField::fromKeyAndContent('about_service_3', 'Service 3'),
        PageField::fromKeyAndContent('about_service_4', 'Service 4'),
        PageField::fromKeyAndContent('about_service_5', 'Service 5'),
        // about_service_6 is missing
    ];

    // And: Services section is enabled
    /** @var SectionVisibilityServiceInterface&MockInterface $sectionService */
    $sectionService = Mockery::mock(SectionVisibilityServiceInterface::class);

    /** @var Expectation $expectation1 */
    $expectation1 = $sectionService->shouldReceive('isSectionEnabled');
    $expectation1->with('experience', 'about')->andReturn(false);

    /** @var Expectation $expectation2 */
    $expectation2 = $sectionService->shouldReceive('isSectionEnabled');
    $expectation2->with('services', 'about')->andReturn(true);

    /** @var Expectation $expectation3 */
    $expectation3 = $sectionService->shouldReceive('isSectionEnabled');
    $expectation3->with('philosophy', 'about')->andReturn(false);

    $page = AboutPage::reconstitute($fields, $sectionService);

    // When/Then
    expect($page->hasMinimumContent())->toBeFalse()
        ->and($page->getMissingFields())->toContain('about_service_6')
        ->and($page->shouldShowServices())->toBeTrue();
});

test('about page has minimum content with philosophy', function () {
    // Given: Hero + bio + philosophy fields exist
    $fields = [
        PageField::fromKeyAndContent('about_title', 'About Me'),
        PageField::fromKeyAndContent('about_intro', 'Introduction'),
        PageField::fromKeyAndContent('about_bio', 'Biography'),
        PageField::fromKeyAndContent('about_philosophy', 'My philosophy...'),
    ];

    // And: Philosophy is enabled, others disabled
    /** @var SectionVisibilityServiceInterface&MockInterface $sectionService */
    $sectionService = Mockery::mock(SectionVisibilityServiceInterface::class);

    /** @var Expectation $expectation1 */
    $expectation1 = $sectionService->shouldReceive('isSectionEnabled');
    $expectation1->with('experience', 'about')->andReturn(false);

    /** @var Expectation $expectation2 */
    $expectation2 = $sectionService->shouldReceive('isSectionEnabled');
    $expectation2->with('services', 'about')->andReturn(false);

    /** @var Expectation $expectation3 */
    $expectation3 = $sectionService->shouldReceive('isSectionEnabled');
    $expectation3->with('philosophy', 'about')->andReturn(true);

    $page = AboutPage::reconstitute($fields, $sectionService);

    // When/Then
    expect($page->hasMinimumContent())->toBeTrue()
        ->and($page->getMissingFields())->toBeEmpty()
        ->and($page->shouldShowPhilosophy())->toBeTrue();
});

test('about page with all sections enabled and complete', function () {
    // Given: All fields exist
    $fields = [
        // Hero
        PageField::fromKeyAndContent('about_title', 'About Me'),
        PageField::fromKeyAndContent('about_intro', 'Introduction'),
        PageField::fromKeyAndContent('about_bio', 'Biography'),
        // Experience
        PageField::fromKeyAndContent('about_experience_years', '10+'),
        PageField::fromKeyAndContent('about_experience_projects', '50+'),
        PageField::fromKeyAndContent('about_experience_clients', '30+'),
        // Services
        PageField::fromKeyAndContent('about_service_1', 'Service 1'),
        PageField::fromKeyAndContent('about_service_2', 'Service 2'),
        PageField::fromKeyAndContent('about_service_3', 'Service 3'),
        PageField::fromKeyAndContent('about_service_4', 'Service 4'),
        PageField::fromKeyAndContent('about_service_5', 'Service 5'),
        PageField::fromKeyAndContent('about_service_6', 'Service 6'),
        // Philosophy
        PageField::fromKeyAndContent('about_philosophy', 'My philosophy...'),
    ];

    // And: All sections enabled
    /** @var SectionVisibilityServiceInterface&MockInterface $sectionService */
    $sectionService = Mockery::mock(SectionVisibilityServiceInterface::class);

    /** @var Expectation $expectation1 */
    $expectation1 = $sectionService->shouldReceive('isSectionEnabled');
    $expectation1->with('experience', 'about')->andReturn(true);

    /** @var Expectation $expectation2 */
    $expectation2 = $sectionService->shouldReceive('isSectionEnabled');
    $expectation2->with('services', 'about')->andReturn(true);

    /** @var Expectation $expectation3 */
    $expectation3 = $sectionService->shouldReceive('isSectionEnabled');
    $expectation3->with('philosophy', 'about')->andReturn(true);

    $page = AboutPage::reconstitute($fields, $sectionService);

    // When/Then
    expect($page->hasMinimumContent())->toBeTrue()
        ->and($page->getMissingFields())->toBeEmpty();
});
