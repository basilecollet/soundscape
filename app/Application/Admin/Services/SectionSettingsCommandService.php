<?php

declare(strict_types=1);

namespace App\Application\Admin\Services;

use App\Domain\Admin\Enums\SectionKeys;
use App\Domain\Admin\Repositories\SectionSettingRepository;
use App\Domain\Admin\Services\SectionSettingsCommandInterface;

/**
 * Command Service for Section Settings (Admin - Write Operations)
 *
 * This service handles all WRITE operations related to section settings.
 * Used by Admin interface to configure which sections are visible on each page.
 *
 * CQRS Pattern: This is the Command side (Write operations).
 */
readonly class SectionSettingsCommandService implements SectionSettingsCommandInterface
{
    public function __construct(
        private SectionSettingRepository $sectionSettingRepository
    ) {}

    /**
     * Enable or disable a section for a page
     */
    public function setSectionEnabled(string $sectionKey, string $page, bool $enabled): bool
    {
        if (! SectionKeys::isValidSectionForPage($sectionKey, $page)) {
            return false; // Cannot modify non-disableable sections
        }

        $this->sectionSettingRepository->setSectionEnabled($sectionKey, $page, $enabled);

        return true;
    }

    /**
     * Get available section settings for the admin interface
     *
     * @return array<string, list<array{section_key: string, label: string, is_enabled: bool}>>
     */
    public function getAvailableSectionSettings(): array
    {
        $settings = [];

        foreach (SectionKeys::getAvailablePages() as $page) {
            $disableableSections = SectionKeys::getDisableableSectionsForPage($page);
            $pageSettings = [];

            foreach ($disableableSections as $sectionKey) {
                $pageSettings[] = [
                    'section_key' => $sectionKey,
                    'label' => __(sprintf('admin.settings.sections.%s.%s.title', $page, $sectionKey)),
                    'is_enabled' => $this->sectionSettingRepository->isSectionEnabled($sectionKey, $page),
                ];
            }

            $settings[$page] = $pageSettings;
        }

        return $settings;
    }

    /**
     * Bulk update section settings
     *
     * @param  array<array{section_key: string, page: string, is_enabled: bool}>  $settings
     */
    public function bulkUpdateSectionSettings(array $settings): void
    {
        // Filter only valid settings
        $validSettings = array_filter($settings, function ($setting) {
            return SectionKeys::isValidSectionForPage($setting['section_key'], $setting['page']);
        });

        $this->sectionSettingRepository->bulkUpdateSettings($validSettings);
    }
}
