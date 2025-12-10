<?php

declare(strict_types=1);

namespace App\Application\Portfolio\Services;

use App\Domain\Admin\Enums\SectionKeys;
use App\Domain\Admin\Repositories\SectionSettingRepository;
use App\Domain\Portfolio\Services\SectionVisibilityServiceInterface;

readonly class SectionVisibilityService implements SectionVisibilityServiceInterface
{
    public function __construct(
        private SectionSettingRepository $sectionSettingRepository
    ) {}

    /**
     * Check if a section is enabled for a given page
     */
    public function isSectionEnabled(string $sectionKey, string $page): bool
    {
        // First check if the section is actually disableable for this page
        if (! SectionKeys::isValidSectionForPage($sectionKey, $page)) {
            // If it's not a disableable section, it's always enabled (like hero sections)
            return true;
        }

        return $this->sectionSettingRepository->isSectionEnabled($sectionKey, $page);
    }

    /**
     * Enable or disable a section for a page
     */
    public function setSectionEnabled(string $sectionKey, string $page, bool $enabled): bool
    {
        if (! SectionKeys::isValidSectionForPage($sectionKey, $page)) {
            return false; // Cannot disable non-disableable sections
        }

        $this->sectionSettingRepository->setSectionEnabled($sectionKey, $page, $enabled);

        return true;
    }

    /**
     * Get all enabled sections for a page, including non-disableable ones
     *
     * @return array<string>
     */
    public function getEnabledSectionsForPage(string $page): array
    {
        // Get disableable sections that are enabled
        $enabledDisableableSections = $this->sectionSettingRepository->getEnabledSectionsForPage($page);

        // Get non-disableable sections (hero sections are always enabled)
        $nonDisableableSections = $this->getNonDisableableSectionsForPage($page);

        return array_merge($enabledDisableableSections, $nonDisableableSections);
    }

    /**
     * Get sections that cannot be disabled for a page
     *
     * @return array<string>
     */
    public function getNonDisableableSectionsForPage(string $page): array
    {
        return match ($page) {
            'home' => ['hero'], // Hero section is always enabled
            'about' => ['hero', 'bio'], // Hero and bio sections are always enabled
            'contact' => ['hero', 'form', 'info'], // All contact sections are always enabled
            default => [],
        };
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
                    'label' => __("admin.settings.sections.{$page}.{$sectionKey}.title"),
                    'is_enabled' => $this->isSectionEnabled($sectionKey, $page),
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
