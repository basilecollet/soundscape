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
        $this->homeContent = PageContent::getContent('home_text');
    }

    public function render(): View
    {
        return view('livewire.components.home-section');
    }
}
