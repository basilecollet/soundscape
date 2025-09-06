<?php

namespace App\Livewire\Admin;

use App\Application\Admin\Services\SectionVisibilityService;
use App\Domain\Admin\Enums\SectionKeys;
use Livewire\Component;

class SectionSettingsManager extends Component
{
    public array $sectionSettings = [];

    public function mount(SectionVisibilityService $sectionVisibilityService): void
    {
        $this->loadSectionSettings($sectionVisibilityService);
    }

    public function toggleSection(string $sectionKey, string $page, SectionVisibilityService $sectionVisibilityService): void
    {
        $currentState = $this->sectionSettings[$page][$sectionKey] ?? true;
        $newState = ! $currentState;

        if ($sectionVisibilityService->setSectionEnabled($sectionKey, $page, $newState)) {
            $this->sectionSettings[$page][$sectionKey] = $newState;

            $label = SectionKeys::getLabel($sectionKey);
            $status = $newState ? 'enabled' : 'disabled';

            session()->flash('message', "Section '{$label}' has been {$status}.");
        } else {
            session()->flash('error', 'Unable to modify this section.');
        }
    }

    public function render(SectionVisibilityService $sectionVisibilityService): \Illuminate\View\View
    {
        $availableSettings = $sectionVisibilityService->getAvailableSectionSettings();

        return view('livewire.admin.section-settings-manager', [
            'availableSettings' => $availableSettings,
        ]);
    }

    private function loadSectionSettings(SectionVisibilityService $sectionVisibilityService): void
    {
        $this->sectionSettings = [];

        foreach (SectionKeys::getAvailablePages() as $page) {
            $this->sectionSettings[$page] = [];

            foreach (SectionKeys::getDisableableSectionsForPage($page) as $sectionKey) {
                $this->sectionSettings[$page][$sectionKey] = $sectionVisibilityService->isSectionEnabled($sectionKey, $page);
            }
        }
    }
}
