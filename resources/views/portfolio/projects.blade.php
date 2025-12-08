@extends('layouts.portfolio')

@section('content')
    <!-- Hero Section -->
    <x-portfolio.hero-section
        :title="__('portfolio.projects.title')"
        :subtitle="__('portfolio.projects.subtitle')"
        :description="__('portfolio.projects.description')"
        :showIllustration="false"
        :ctaLinks="[]"
    />

    <!-- Projects Grid Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-6 lg:px-12">
            @if($projects->isNotEmpty())
                <!-- Projects Count -->
                <div class="mb-8">
                    <p class="text-sm text-portfolio-text/60">
                        <span class="font-medium text-portfolio-accent">{{ $projects->count() }}</span>
                        {{ $projects->count() === 1 ? __('portfolio.projects.project_singular') : __('portfolio.projects.project_plural') }}
                    </p>
                </div>

                <!-- Grid: 1 col mobile, 2 cols tablet, 3 cols desktop -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($projects as $project)
                        <x-portfolio.project-card
                            :title="$project->title"
                            :slug="$project->slug"
                            :shortDescription="$project->shortDescription"
                            :projectDate="$project->projectDate"
                            :featuredImage="$project->featuredImage"
                        />
                    @endforeach
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-20">
                    <div class="max-w-md mx-auto space-y-4">
                        <div class="flex justify-center">
                            <svg class="w-16 h-16 text-portfolio-accent/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                        <p class="text-lg text-portfolio-text/70">{{ __('portfolio.projects.empty_title') }}</p>
                        <p class="text-sm text-portfolio-text/50">{{ __('portfolio.projects.empty_description') }}</p>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <!-- Divider -->
    <div class="h-px bg-gradient-to-r from-transparent via-portfolio-accent/30 to-transparent"></div>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-b from-portfolio-light to-portfolio-secondary">
        <div class="container mx-auto px-6 lg:px-12 text-center">
            <div class="max-w-3xl mx-auto space-y-6">
                <h2 class="text-3xl md:text-4xl font-bold text-portfolio-dark">{{ __('portfolio.home.cta.ready_title') }}</h2>
                <p class="text-lg text-portfolio-text/80">{{ __('portfolio.home.cta.ready_description') }}</p>
                <div class="pt-4">
                    <x-portfolio.cta-link href="{{ route('contact') }}" :primary="true">
                        {{ __('portfolio.home.cta.get_in_touch') }}
                    </x-portfolio.cta-link>
                </div>
            </div>
        </div>
    </section>
@endsection