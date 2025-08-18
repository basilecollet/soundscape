<?php

namespace App\Livewire\Components;

use App\Models\PageContent;
use Illuminate\View\View;
use Livewire\Component;

class ContactSection extends Component
{
    public string $contactContent;

    public function mount(): void
    {
        $this->contactContent = PageContent::getContent('contact_text', 'Contact us');
    }

    public function render(): View
    {
        return view('livewire.components.contact-section');
    }
}
