<?php

declare(strict_types=1);

use App\Application\Portfolio\Services\SectionVisibilityQueryService;
use App\Domain\Admin\Repositories\SectionSettingRepository;

beforeEach(function () {
    /** @var SectionSettingRepository&\Mockery\MockInterface $repository */
    $repository = Mockery::mock(SectionSettingRepository::class);
    $this->repository = $repository;
    $this->service = new SectionVisibilityQueryService($repository);
});

describe('isSectionEnabled', function () {
    it('returns true for non-disableable sections', function () {
        // Hero section is not disableable for home page
        $result = $this->service->isSectionEnabled('hero', 'home');

        expect($result)->toBeTrue();
    });

    it('checks repository for disableable sections when enabled', function () {
        $this->repository
            ->shouldReceive('isSectionEnabled')
            ->once()
            ->with('features', 'home')
            ->andReturn(true);

        $result = $this->service->isSectionEnabled('features', 'home');

        expect($result)->toBeTrue();
    });

    it('checks repository for disableable sections when disabled', function () {
        $this->repository
            ->shouldReceive('isSectionEnabled')
            ->once()
            ->with('features', 'home')
            ->andReturn(false);

        $result = $this->service->isSectionEnabled('features', 'home');

        expect($result)->toBeFalse();
    });
});

describe('getEnabledSectionsForPage', function () {
    it('returns merge of enabled disableable and non-disableable sections for home', function () {
        $this->repository
            ->shouldReceive('getEnabledSectionsForPage')
            ->once()
            ->with('home')
            ->andReturn(['features', 'cta']);

        $result = $this->service->getEnabledSectionsForPage('home');

        expect($result)->toBe(['features', 'cta', 'hero']);
    });

    it('returns merge of enabled disableable and non-disableable sections for about', function () {
        $this->repository
            ->shouldReceive('getEnabledSectionsForPage')
            ->once()
            ->with('about')
            ->andReturn(['experience', 'services']);

        $result = $this->service->getEnabledSectionsForPage('about');

        expect($result)->toBe(['experience', 'services', 'hero', 'bio']);
    });

    it('returns only non-disableable sections when no disableable sections enabled', function () {
        $this->repository
            ->shouldReceive('getEnabledSectionsForPage')
            ->once()
            ->with('home')
            ->andReturn([]);

        $result = $this->service->getEnabledSectionsForPage('home');

        expect($result)->toBe(['hero']);
    });
});

describe('getNonDisableableSectionsForPage', function () {
    it('returns hero for home page', function () {
        $result = $this->service->getNonDisableableSectionsForPage('home');

        expect($result)->toBe(['hero']);
    });

    it('returns hero and bio for about page', function () {
        $result = $this->service->getNonDisableableSectionsForPage('about');

        expect($result)->toBe(['hero', 'bio']);
    });

    it('returns hero, form, and info for contact page', function () {
        $result = $this->service->getNonDisableableSectionsForPage('contact');

        expect($result)->toBe(['hero', 'form', 'info']);
    });

    it('returns empty array for unknown page', function () {
        $result = $this->service->getNonDisableableSectionsForPage('unknown');

        expect($result)->toBe([]);
    });
});
