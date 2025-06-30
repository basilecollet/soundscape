<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.home')] class extends Component {
    // No specific properties or methods needed in the main component
    // as each section component handles its own data
}; ?>

<div>
    <livewire:components.navbar />
    <livewire:components.home-section />
    <livewire:components.about-section />
    <livewire:components.contact-section />
    <livewire:components.footer />
</div>
