<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use Livewire\Component;

class ContentEdit extends Component
{
    public int $contentId;

    public function mount(int $contentId): void
    {
        $this->contentId = $contentId;
    }

    public function render()
    {
        return view('livewire.admin.content-edit');
    }
}
