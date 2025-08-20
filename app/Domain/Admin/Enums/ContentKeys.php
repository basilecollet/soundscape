<?php

declare(strict_types=1);

namespace App\Domain\Admin\Enums;

enum ContentKeys: string
{
    // HOME PAGE
    case HOME_HERO = 'home_hero';
    case HOME_TEXT = 'home_text';
    case HOME_SERVICES = 'home_services';
    case HOME_TESTIMONIALS = 'home_testimonials';

    // ABOUT PAGE
    case ABOUT_HERO = 'about_hero';
    case ABOUT_SECTION_1 = 'about_section_1';
    case ABOUT_SECTION_2 = 'about_section_2';
    case ABOUT_SECTION_3 = 'about_section_3';
    case ABOUT_TEAM = 'about_team';
    case ABOUT_MISSION = 'about_mission';

    // CONTACT PAGE
    case CONTACT_HERO = 'contact_hero';
    case CONTACT_TEXT = 'contact_text';
    case CONTACT_INFO = 'contact_info';
    case CONTACT_HOURS = 'contact_hours';

    /**
     * Get all keys for a specific page
     *
     * @return array<string>
     */
    public static function getKeysForPage(string $page): array
    {
        return match ($page) {
            'home' => [
                self::HOME_HERO->value,
                self::HOME_TEXT->value,
                self::HOME_SERVICES->value,
                self::HOME_TESTIMONIALS->value,
            ],
            'about' => [
                self::ABOUT_HERO->value,
                self::ABOUT_SECTION_1->value,
                self::ABOUT_SECTION_2->value,
                self::ABOUT_SECTION_3->value,
                self::ABOUT_TEAM->value,
                self::ABOUT_MISSION->value,
            ],
            'contact' => [
                self::CONTACT_HERO->value,
                self::CONTACT_TEXT->value,
                self::CONTACT_INFO->value,
                self::CONTACT_HOURS->value,
            ],
            default => [],
        };
    }

    /**
     * Get available pages
     *
     * @return array<string>
     */
    public static function getAvailablePages(): array
    {
        return ['home', 'about', 'contact'];
    }

    /**
     * Get all keys
     *
     * @return array<string>
     */
    public static function getAllKeys(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }

    /**
     * Get human-readable label for a key
     */
    public static function getLabel(string $key): string
    {
        return match ($key) {
            self::HOME_HERO->value => 'Hero Section',
            self::HOME_TEXT->value => 'Welcome Text',
            self::HOME_SERVICES->value => 'Services Section',
            self::HOME_TESTIMONIALS->value => 'Testimonials',
            
            self::ABOUT_HERO->value => 'Hero Section',
            self::ABOUT_SECTION_1->value => 'About Text 1',
            self::ABOUT_SECTION_2->value => 'About Text 2',
            self::ABOUT_SECTION_3->value => 'About Text 3',
            self::ABOUT_TEAM->value => 'Team Section',
            self::ABOUT_MISSION->value => 'Mission & Vision',
            
            self::CONTACT_HERO->value => 'Hero Section',
            self::CONTACT_TEXT->value => 'Contact Text',
            self::CONTACT_INFO->value => 'Contact Information',
            self::CONTACT_HOURS->value => 'Opening Hours',
            
            default => ucwords(str_replace('_', ' ', $key)),
        };
    }

    /**
     * Validate if a key is valid for a page
     */
    public static function isValidKeyForPage(string $key, string $page): bool
    {
        return in_array($key, self::getKeysForPage($page), true);
    }
}