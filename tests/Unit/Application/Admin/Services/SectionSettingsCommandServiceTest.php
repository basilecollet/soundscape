<?php

declare(strict_types=1);

use App\Application\Admin\Services\SectionSettingsCommandService;
use App\Domain\Admin\Repositories\SectionSettingRepository;

beforeEach(function () {
    $this->repository = Mockery::mock(SectionSettingRepository::class);
    $this->service = new SectionSettingsCommandService($this->repository);
});

describe('setSectionEnabled', function () {
    it('enables a valid disableable section', function () {
        $this->repository
            ->shouldReceive('setSectionEnabled')
            ->once()
            ->with('features', 'home', true);

        $result = $this->service->setSectionEnabled('features', 'home', true);

        expect($result)->toBeTrue();
    });

    it('disables a valid disableable section', function () {
        $this->repository
            ->shouldReceive('setSectionEnabled')
            ->once()
            ->with('features', 'home', false);

        $result = $this->service->setSectionEnabled('features', 'home', false);

        expect($result)->toBeTrue();
    });

    it('returns false for non-disableable sections', function () {
        // Hero section cannot be disabled
        $result = $this->service->setSectionEnabled('hero', 'home', false);

        expect($result)->toBeFalse();
    });

    it('returns false for invalid section-page combinations', function () {
        // 'features' doesn't exist on 'about' page
        $result = $this->service->setSectionEnabled('invalid', 'home', true);

        expect($result)->toBeFalse();
    });
});

// Note: getAvailableSectionSettings() depends on Laravel's translation helper __()
// and is tested in Feature tests instead (SectionVisibilityFeatureTest)

describe('bulkUpdateSectionSettings', function () {
    it('updates only valid settings', function () {
        $settings = [
            ['section_key' => 'features', 'page' => 'home', 'is_enabled' => true],
            ['section_key' => 'cta', 'page' => 'home', 'is_enabled' => false],
            ['section_key' => 'experience', 'page' => 'about', 'is_enabled' => true],
        ];

        $this->repository
            ->shouldReceive('bulkUpdateSettings')
            ->once()
            ->with($settings);

        $this->service->bulkUpdateSectionSettings($settings);
    });

    it('filters out invalid settings before updating', function () {
        $settings = [
            ['section_key' => 'features', 'page' => 'home', 'is_enabled' => true], // Valid
            ['section_key' => 'hero', 'page' => 'home', 'is_enabled' => false], // Invalid: hero is not disableable
            ['section_key' => 'invalid', 'page' => 'home', 'is_enabled' => true], // Invalid: doesn't exist
        ];

        $this->repository
            ->shouldReceive('bulkUpdateSettings')
            ->once()
            ->with([
                ['section_key' => 'features', 'page' => 'home', 'is_enabled' => true],
            ]);

        $this->service->bulkUpdateSectionSettings($settings);
    });

    it('handles empty settings array', function () {
        $this->repository
            ->shouldReceive('bulkUpdateSettings')
            ->once()
            ->with([]);

        $this->service->bulkUpdateSectionSettings([]);
    });
});
