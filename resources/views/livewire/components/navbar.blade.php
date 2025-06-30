<?php

use Livewire\Volt\Component;

new class extends Component {
    // No specific properties or methods needed for the navbar
}; ?>

<nav class="fixed top-0 left-0 w-full z-50 transition-all duration-300"
     x-data="{ scrolled: false }"
     x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 50 })"
     :class="{ 'bg-transparent': !scrolled, 'bg-white shadow-md': scrolled }">
    <div class="container mx-auto px-4 py-4 flex justify-between items-center">
        <div class="text-xl font-bold" :class="{ 'text-white': !scrolled, 'text-gray-800': scrolled }">
            {{ config('app.name') }}
        </div>
        <div class="flex space-x-6">
            <a href="#home" class="transition-colors" :class="{ 'text-white hover:text-gray-200': !scrolled, 'text-gray-800 hover:text-gray-600': scrolled }">Home</a>
            <a href="#about" class="transition-colors" :class="{ 'text-white hover:text-gray-200': !scrolled, 'text-gray-800 hover:text-gray-600': scrolled }">About</a>
            <a href="#contact" class="transition-colors" :class="{ 'text-white hover:text-gray-200': !scrolled, 'text-gray-800 hover:text-gray-600': scrolled }">Contact</a>
        </div>
    </div>
</nav>
