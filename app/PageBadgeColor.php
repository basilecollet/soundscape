<?php

namespace App;

enum PageBadgeColor: string
{
    case Home = 'home';
    case About = 'about';
    case Contact = 'contact';
    case Services = 'services';

    /**
     * Get the Tailwind CSS classes for the badge.
     */
    public function classes(): string
    {
        return match ($this) {
            self::Home => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
            self::About => 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400',
            self::Contact => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
            self::Services => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
        };
    }

    /**
     * Get the enum from a string value.
     */
    public static function fromString(string $page): ?self
    {
        return self::tryFrom($page);
    }

    /**
     * Get the CSS classes for a given page string.
     * Returns default classes if page is not found.
     */
    public static function getClasses(string $page): string
    {
        return self::fromString($page)?->classes()
            ?? 'bg-gray-100 text-gray-800 dark:bg-zinc-800 dark:text-zinc-400';
    }
}
