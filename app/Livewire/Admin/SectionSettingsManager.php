<?php

namespace App\Livewire\Admin;

use App\Application\Portfolio\Services\SectionVisibilityService;
use App\Domain\Admin\Enums\SectionKeys;
use Livewire\Component;

class SectionSettingsManager extends Component
{
    /**
     * Map of page => sectionKey => isEnabled
     *
     * @var array<string, array<string, bool>>
     */
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
            $statusKey = $newState ? 'ui.status.active' : 'ui.status.inactive';

            $message = __('admin.settings.updated_successfully');

            $this->dispatch('section-toggled', ['message' => $message]);
            session()->flash('message', $message);
        } else {
            $message = __('ui.errors.unauthorized');

            $this->dispatch('section-toggle-error', ['message' => $message]);
            session()->flash('error', $message);
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
