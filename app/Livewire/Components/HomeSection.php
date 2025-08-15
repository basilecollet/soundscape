<?php

namespace App\Livewire\Components;

use App\Models\PageContent;
use Livewire\Component;

class HomeSection extends Component
{
    public ?string $homeContent;

    public function mount(): void
    {
        $this->homeContent = PageContent::getContent('home_text');
    }

    public function render()
    {
        return view('livewire.components.home-section');
    }
}