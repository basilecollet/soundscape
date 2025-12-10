<?php

declare(strict_types=1);

namespace App\Domain\Admin\Services;

/**
 * Command Interface for Section Settings (Admin - Write Operations)
 *
 * This interface represents the WRITE side of section settings in CQRS pattern.
 * Used by Admin context to modify section visibility settings.
 */
interface SectionSettingsCommandInterface
{
    /**
     * Enable or disable a section for a page
     *
     * @param  string  $sectionKey  The section identifier
     * @param  string  $page  The page identifier
     * @param  bool  $enabled  True to enable, false to disable
     * @return bool True if the setting was successfully updated, false if the section cannot be modified
     */
    public function setSectionEnabled(string $sectionKey, string $page, bool $enabled): bool;

    /**
     * Get available section settings for the admin interface
     *
     * Returns all sections that can be configured through the admin interface,
     * along with their current enabled/disabled state.
     *
     * @return array<string, list<array{section_key: string, label: string, is_enabled: bool}>> Sections grouped by page
     */
    public function getAvailableSectionSettings(): array;

    /**
     * Bulk update section settings
     *
     * Allows updating multiple section settings in a single operation.
     * Only valid, disableable sections will be updated.
     *
     * @param  array<array{section_key: string, page: string, is_enabled: bool}>  $settings  Array of settings to update
     */
    public function bulkUpdateSectionSettings(array $settings): void;
}
