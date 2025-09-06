<?php

declare(strict_types=1);

namespace App\Domain\Admin\Repositories;

use App\Models\SectionSetting;
use Illuminate\Database\Eloquent\Collection;

interface SectionSettingRepository
{
    /**
     * Find a section setting by section key and page
     */
    public function findBySectionAndPage(string $sectionKey, string $page): ?SectionSetting;

    /**
     * Check if a section is enabled for a page
     */
    public function isSectionEnabled(string $sectionKey, string $page): bool;

    /**
     * Enable or disable a section for a page
     */
    public function setSectionEnabled(string $sectionKey, string $page, bool $enabled): SectionSetting;

    /**
     * Get all section settings for a page
     *
     * @return Collection<int, SectionSetting>
     */
    public function getAllForPage(string $page): Collection;

    /**
     * Get all enabled sections for a page
     *
     * @return array<string>
     */
    public function getEnabledSectionsForPage(string $page): array;

    /**
     * Get all section settings
     *
     * @return Collection<int, SectionSetting>
     */
    public function getAll(): Collection;

    /**
     * Create or update multiple section settings
     *
     * @param  array<array{section_key: string, page: string, is_enabled: bool}>  $settings
     */
    public function bulkUpdateSettings(array $settings): void;

    /**
     * Delete a section setting
     */
    public function delete(string $sectionKey, string $page): bool;
}
