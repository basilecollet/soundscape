<?php

namespace App\Livewire\Components;

use App\Models\PageContent;
use Illuminate\View\View;
use Livewire\Component;

class HomeSection extends Component
{
    public ?string $homeContent;

    public function mount(): void
    {
        $this->homeContent = PageContent::getContent('home_hero_text', 'Transform your audio projects with industry-standard expertise. Professional mixing, mastering, and sound design services.');
    }

    public function render(): View
    {
        return view('livewire.components.home-section');
    }
}
