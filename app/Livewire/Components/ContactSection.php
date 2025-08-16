<?php

namespace App\Livewire\Components;

use App\Models\PageContent;
use Livewire\Component;

class ContactSection extends Component
{
    public string $contactContent;

    public function mount(): void
    {
        $this->contactContent = PageContent::getContent('contact_text', 'Contact us');
    }

    public function render()
    {
        return view('livewire.components.contact-section');
    }
}
