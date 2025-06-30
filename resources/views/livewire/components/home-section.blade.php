<?php

use App\Models\PageContent;
use Livewire\Volt\Component;

new class extends Component {
    public $homeContent;

    public function mount()
    {
        $this->homeContent = PageContent::getContent('home_text');
    }
}; ?>

<section id="home" class="min-h-screen flex items-center bg-gradient-to-b from-[#F2EFE6] to-[#A4B465] pt-16">
    <div class="container mx-auto px-4 py-16 flex flex-col md:flex-row items-center">
        <div class="md:w-1/2 mb-10 md:mb-0">
            <h1 class="text-4xl md:text-5xl font-bold mb-6 text-gray-800">Soundscape Audio</h1>
            <p class="text-lg text-gray-700">
                {{ $homeContent ?? 'Welcome to Soundscape Audio' }}
            </p>
        </div>
        <div class="md:w-1/2 flex justify-center">
            <!-- SVG Globe -->
            <svg class="w-full max-w-md" viewBox="0 0 500 500" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="250" cy="250" r="240" stroke="black" stroke-width="2"/>
                <ellipse cx="250" cy="250" rx="240" ry="100" stroke="black" stroke-width="2"/>
                <line x1="10" y1="250" x2="490" y2="250" stroke="black" stroke-width="2"/>
                <line x1="250" y1="10" x2="250" y2="490" stroke="black" stroke-width="2"/>
                <path d="M250 10 C 350 100, 400 200, 250 490" stroke="black" stroke-width="2" fill="none"/>
                <path d="M250 10 C 150 100, 100 200, 250 490" stroke="black" stroke-width="2" fill="none"/>
                <path d="M10 250 C 100 350, 200 400, 490 250" stroke="black" stroke-width="2" fill="none"/>
                <path d="M10 250 C 100 150, 200 100, 490 250" stroke="black" stroke-width="2" fill="none"/>
            </svg>
        </div>
    </div>
</section>
