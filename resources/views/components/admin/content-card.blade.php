@props([
    'content' => null
])

<div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm hover:shadow-md transition-shadow duration-200">
    <!-- Header: Key + Page Badge -->
    <div class="flex justify-between items-start mb-2">
        <!-- Key with "new" indicator -->
        <div class="flex items-center gap-2">
            <code class="text-xs font-mono bg-gray-100 px-2 py-1 rounded">
                {{ $content->key }}
            </code>
            @if($content->updated_at->gt(now()->subDay()))
                <span class="flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-2 w-2 rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                </span>
            @endif
        </div>

        <!-- Page Badge (colored) -->
        @php
            $pageColors = [
                'home' => 'bg-blue-100 text-blue-800',
                'about' => 'bg-purple-100 text-purple-800',
                'contact' => 'bg-green-100 text-green-800',
                'services' => 'bg-yellow-100 text-yellow-800',
            ];
            $colorClass = $pageColors[$content->page] ?? 'bg-gray-100 text-gray-800';
        @endphp
        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium shrink-0 {{ $colorClass }}">
            <svg class="mr-1 h-2 w-2" fill="currentColor" viewBox="0 0 8 8">
                <circle cx="4" cy="4" r="3" />
            </svg>
            {{ ucfirst($content->page) }}
        </span>
    </div>

    <!-- Title Section -->
    <div class="mb-3">
        @if($content->title)
            <div class="font-medium text-gray-900 text-sm line-clamp-2">{{ $content->title }}</div>
            <div class="text-gray-500 text-xs mt-0.5">{{ ucfirst(str_replace('_', ' ', $content->key)) }}</div>
        @else
            <div class="font-medium text-gray-500 text-sm">{{ ucfirst(str_replace('_', ' ', $content->key)) }}</div>
            <div class="text-gray-400 text-xs">No title set</div>
        @endif
    </div>

    <!-- Content Preview Section -->
    @if($content->content)
        <div class="mb-3 pb-3 border-b border-gray-100">
            <p class="text-sm text-gray-600 line-clamp-2 mb-1">
                {{ Str::limit($content->content, 80) }}
            </p>
            <p class="text-xs text-gray-400">
                {{ strlen($content->content) }} characters
            </p>
        </div>
    @else
        <div class="mb-3 pb-3 border-b border-gray-100">
            <span class="text-sm italic text-gray-400">Empty content</span>
        </div>
    @endif

    <!-- Actions -->
    <div class="flex gap-2">
        <!-- Edit Button -->
        <flux:button
            size="sm"
            variant="ghost"
            href="{{ route('admin.content.edit', $content->id) }}"
            wire:navigate
            class="flex-1"
        >
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
            Edit
        </flux:button>

        <!-- Copy Button (only if content exists) -->
        @if($content->content)
            <flux:button
                size="sm"
                variant="ghost"
                x-data="{ copied: false }"
                @click="
                    navigator.clipboard.writeText('{{ addslashes($content->content) }}');
                    copied = true;
                    setTimeout(() => copied = false, 2000);
                "
                class="flex-1"
            >
                <svg x-show="!copied" class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                </svg>
                <svg x-show="copied" x-cloak class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span x-text="copied ? 'Copied!' : 'Copy'"></span>
            </flux:button>
        @endif
    </div>
</div>
