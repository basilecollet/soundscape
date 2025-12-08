@props([
    'title' => '',
    'slug' => '',
    'shortDescription' => null,
    'projectDate' => null,
    'featuredImage' => null,
])

<a href="{{ route('projects.show', ['project' => $slug]) }}" class="block group bg-portfolio-light rounded-xl overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border border-portfolio-accent/10">
    <!-- Image -->
    @if($featuredImage)
        <div class="aspect-video overflow-hidden bg-portfolio-secondary">
            <img
                src="{{ $featuredImage->thumbUrl }}"
                alt="{{ $featuredImage->alt ?? $title }}"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                loading="lazy"
            >
        </div>
    @else
        <!-- Placeholder when no image -->
        <div class="aspect-video bg-gradient-to-br from-portfolio-accent/20 to-portfolio-accent/5 flex items-center justify-center">
            <svg class="w-16 h-16 text-portfolio-accent/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
            </svg>
        </div>
    @endif

    <!-- Content -->
    <div class="p-6 space-y-3">
        <!-- Title -->
        <h3 class="text-xl font-semibold text-portfolio-dark group-hover:text-portfolio-accent transition-colors duration-200">
            {{ $title }}
        </h3>

        <!-- Description -->
        @if($shortDescription)
            <p class="text-sm text-portfolio-text/70 leading-relaxed line-clamp-2">
                {{ $shortDescription }}
            </p>
        @endif

        <!-- Footer: Date -->
        <div class="flex items-center justify-between pt-2 border-t border-portfolio-accent/10">
            @if($projectDate)
                <div class="flex items-center gap-2 text-xs text-portfolio-accent">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <time datetime="{{ $projectDate }}">
                        {{ \Carbon\Carbon::parse($projectDate)->format('M Y') }}
                    </time>
                </div>
            @else
                <div></div>
            @endif

            <!-- View project arrow -->
            <div class="flex items-center gap-1 text-xs font-medium text-portfolio-accent group-hover:gap-2 transition-all duration-200">
                <span>{{ __('portfolio.projects.view') }}</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </div>
        </div>
    </div>
</a>
