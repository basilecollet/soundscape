<?php

declare(strict_types=1);

namespace App\Infra\Repositories\Admin;

use App\Domain\Admin\Repositories\SectionSettingRepository;
use App\Models\SectionSetting;
use Illuminate\Database\Eloquent\Collection;

class SectionSettingDatabaseRepository implements SectionSettingRepository
{
    public function findBySectionAndPage(string $sectionKey, string $page): ?SectionSetting
    {
        return SectionSetting::where('section_key', $sectionKey)
            ->where('page', $page)
            ->first();
    }

    public function isSectionEnabled(string $sectionKey, string $page): bool
    {
        $setting = $this->findBySectionAndPage($sectionKey, $page);

        return $setting ? $setting->is_enabled : true; // Default to enabled if no setting exists
    }

    public function setSectionEnabled(string $sectionKey, string $page, bool $enabled): SectionSetting
    {
        return SectionSetting::updateOrCreate(
            ['section_key' => $sectionKey, 'page' => $page],
            ['is_enabled' => $enabled]
        );
    }

    public function getAllForPage(string $page): Collection
    {
        return SectionSetting::where('page', $page)
            ->orderBy('section_key')
            ->get();
    }

    public function getEnabledSectionsForPage(string $page): array
    {
        return SectionSetting::where('page', $page)
            ->where('is_enabled', true)
            ->pluck('section_key')
            ->toArray();
    }

    public function getAll(): Collection
    {
        return SectionSetting::orderBy('page')
            ->orderBy('section_key')
            ->get();
    }

    public function bulkUpdateSettings(array $settings): void
    {
        foreach ($settings as $setting) {
            $this->setSectionEnabled(
                $setting['section_key'],
                $setting['page'],
                $setting['is_enabled']
            );
        }
    }

    public function delete(string $sectionKey, string $page): bool
    {
        return SectionSetting::where('section_key', $sectionKey)
            ->where('page', $page)
            ->delete() > 0;
    }
}
