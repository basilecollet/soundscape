<?php

namespace App\Livewire\Components;

use App\Models\PageContent;
use Illuminate\View\View;
use Livewire\Component;

class AboutSection extends Component
{
    /** @var array<string, string> */
    public array $aboutContent = [];

    public function mount(): void
    {
        $this->aboutContent['about_section_1'] = PageContent::getContent('about_section_1');
        $this->aboutContent['about_section_2'] = PageContent::getContent('about_section_2');
        $this->aboutContent['about_section_3'] = PageContent::getContent('about_section_3');
    }

    public function render(): View
    {
        return view('livewire.components.about-section');
    }
}
