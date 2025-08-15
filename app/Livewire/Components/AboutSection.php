<?php

namespace App\Livewire\Components;

use App\Models\PageContent;
use Livewire\Component;

class AboutSection extends Component
{
    public array $aboutContent = [];

    public function mount(): void
    {
        $this->aboutContent['about_section_1'] = PageContent::getContent('about_section_1');
        $this->aboutContent['about_section_2'] = PageContent::getContent('about_section_2');
        $this->aboutContent['about_section_3'] = PageContent::getContent('about_section_3');
    }

    public function render()
    {
        return view('livewire.components.about-section');
    }
}