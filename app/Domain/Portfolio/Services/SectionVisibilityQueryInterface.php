<?php

declare(strict_types=1);

namespace App\Domain\Portfolio\Services;

/**
 * Query Interface for Section Visibility (Portfolio - Read Operations)
 *
 * This interface represents the READ side of section visibility in CQRS pattern.
 * Used by Portfolio context to check which sections should be displayed.
 */
interface SectionVisibilityQueryInterface
{
    /**
     * Check if a section is enabled for a given page
     *
     * @param  string  $sectionKey  The section identifier (e.g., 'features', 'cta')
     * @param  string  $page  The page identifier (e.g., 'home', 'about')
     * @return bool True if the section is enabled and should be displayed
     */
    public function isSectionEnabled(string $sectionKey, string $page): bool;

    /**
     * Get all enabled sections for a page, including non-disableable ones
     *
     * Returns all sections that should be displayed on the given page.
     * Includes both explicitly enabled sections and sections that cannot be disabled.
     *
     * @param  string  $page  The page identifier
     * @return array<string> Array of enabled section keys
     */
    public function getEnabledSectionsForPage(string $page): array;

    /**
     * Get sections that cannot be disabled for a page
     *
     * Returns sections that are always visible and cannot be toggled off.
     * Example: Hero sections are typically always enabled.
     *
     * @param  string  $page  The page identifier
     * @return array<string> Array of non-disableable section keys
     */
    public function getNonDisableableSectionsForPage(string $page): array;
}
