<?php

declare(strict_types=1);

namespace App\Domain\Admin\Services;

interface SectionVisibilityServiceInterface
{
    /**
     * Check if a section is enabled for a given page
     */
    public function isSectionEnabled(string $sectionKey, string $page): bool;

    /**
     * Enable or disable a section for a page
     */
    public function setSectionEnabled(string $sectionKey, string $page, bool $enabled): bool;

    /**
     * Get all enabled sections for a page, including non-disableable ones
     *
     * @return array<string>
     */
    public function getEnabledSectionsForPage(string $page): array;

    /**
     * Get sections that cannot be disabled for a page
     *
     * @return array<string>
     */
    public function getNonDisableableSectionsForPage(string $page): array;

    /**
     * Get available section settings for the admin interface
     *
     * @return array<string, list<array{section_key: string, label: string, is_enabled: bool}>>
     */
    public function getAvailableSectionSettings(): array;

    /**
     * Bulk update section settings
     *
     * @param  array<array{section_key: string, page: string, is_enabled: bool}>  $settings
     */
    public function bulkUpdateSectionSettings(array $settings): void;
}
