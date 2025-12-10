<?php

namespace App\Livewire\Admin;

use App\Domain\Admin\Enums\SectionKeys;
use App\Domain\Admin\Services\SectionSettingsCommandInterface;
use Livewire\Component;

class SectionSettingsManager extends Component
{
    /**
     * Map of page => sectionKey => isEnabled
     *
     * @var array<string, array<string, bool>>
     */
    public array $sectionSettings = [];

    public function mount(SectionSettingsCommandInterface $sectionSettingsService): void
    {
        $this->loadSectionSettings($sectionSettingsService);
    }

    public function toggleSection(string $sectionKey, string $page, SectionSettingsCommandInterface $sectionSettingsService): void
    {
        $currentState = $this->sectionSettings[$page][$sectionKey] ?? false;
        $newState = ! $currentState;

        if ($sectionSettingsService->setSectionEnabled($sectionKey, $page, $newState)) {
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

    public function render(SectionSettingsCommandInterface $sectionSettingsService): \Illuminate\View\View
    {
        $availableSettings = $sectionSettingsService->getAvailableSectionSettings();

        return view('livewire.admin.section-settings-manager', [
            'availableSettings' => $availableSettings,
        ]);
    }

    private function loadSectionSettings(SectionSettingsCommandInterface $sectionSettingsService): void
    {
        $availableSettings = $sectionSettingsService->getAvailableSectionSettings();

        $this->sectionSettings = [];

        foreach ($availableSettings as $page => $sections) {
            $this->sectionSettings[$page] = [];

            foreach ($sections as $section) {
                $this->sectionSettings[$page][$section['section_key']] = $section['is_enabled'];
            }
        }
    }
}
