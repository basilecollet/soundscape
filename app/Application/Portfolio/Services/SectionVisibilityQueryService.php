<?php

declare(strict_types=1);

namespace App\Application\Portfolio\Services;

use App\Domain\Admin\Enums\SectionKeys;
use App\Domain\Admin\Repositories\SectionSettingRepository;
use App\Domain\Portfolio\Services\SectionVisibilityQueryInterface;

/**
 * Query Service for Section Visibility (Portfolio - Read Operations)
 *
 * This service handles all READ operations related to section visibility.
 * Used by Portfolio controllers and entities to determine which sections to display.
 *
 * CQRS Pattern: This is the Query side (Read-only operations).
 */
readonly class SectionVisibilityQueryService implements SectionVisibilityQueryInterface
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
}
