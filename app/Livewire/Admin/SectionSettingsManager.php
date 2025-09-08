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
        $currentState = $sectionVisibilityService->isSectionEnabled($sectionKey, $page);
        $newState = ! $currentState;

        if ($sectionVisibilityService->setSectionEnabled($sectionKey, $page, $newState)) {
            // Update local state for immediate UI feedback
            $this->sectionSettings[$page][$sectionKey] = $newState;

            $label = SectionKeys::getLabel($sectionKey);
            $status = $newState ? 'enabled' : 'disabled';

            $this->dispatch('section-toggled', ['message' => "Section '{$label}' has been {$status}."]);
            session()->flash('message', "Section '{$label}' has been {$status}.");
        } else {
            $this->dispatch('section-toggle-error', ['message' => 'Unable to modify this section.']);
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
