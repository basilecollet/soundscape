<?php

namespace App\Models;

use Database\Factories\SectionSettingFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SectionSetting extends Model
{
    /** @use HasFactory<SectionSettingFactory> */
    use HasFactory;

    protected $fillable = [
        'section_key',
        'page',
        'is_enabled',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
    ];

    /**
     * Check if a section is enabled for a given page
     */
    public static function isSectionEnabled(string $sectionKey, string $page): bool
    {
        $setting = self::where('section_key', $sectionKey)
            ->where('page', $page)
            ->first();

        return $setting ? $setting->is_enabled : true; // Default to enabled if no setting exists
    }

    /**
     * Enable or disable a section for a page
     */
    public static function setSectionEnabled(string $sectionKey, string $page, bool $enabled): self
    {
        return self::updateOrCreate(
            ['section_key' => $sectionKey, 'page' => $page],
            ['is_enabled' => $enabled]
        );
    }

    /**
     * Get all enabled sections for a page
     *
     * @return array<string>
     */
    public static function getEnabledSections(string $page): array
    {
        return self::where('page', $page)
            ->where('is_enabled', true)
            ->pluck('section_key')
            ->toArray();
    }
}
