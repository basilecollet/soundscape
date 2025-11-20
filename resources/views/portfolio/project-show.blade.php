@php use Illuminate\Support\Carbon; @endphp
@extends('layouts.portfolio')

@section('content')
    <!-- Hero Section: Project Info + Featured Image -->
    <section
            class="min-h-screen flex items-center pt-20 pb-12 bg-gradient-to-b from-portfolio-secondary via-portfolio-light to-portfolio-accent/10">
        <div class="container mx-auto px-6 lg:px-12">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <!-- Content: Title, Date, Short Description -->
                <div class="space-y-8">
                    <!-- Title -->
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-portfolio-dark leading-tight">
                        {{ $project->title }}
                    </h1>

                    <!-- Date -->
                    @if($project->projectDate)
                        <div class="flex items-center gap-2 text-portfolio-accent font-medium">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <time datetime="{{ $project->projectDate }}">
                                {{ Carbon::parse($project->projectDate)->format('F Y') }}
                            </time>
                        </div>
                    @endif

                    <!-- Short Description -->
                    @if($project->shortDescription)
                        <p class="text-base md:text-lg text-portfolio-text/70 leading-relaxed max-w-xl">
                            {{ $project->shortDescription }}
                        </p>
                    @endif
                </div>

                <!-- Featured Image -->
                <div class="flex justify-center lg:justify-end">
                    @if($project->featuredImage)
                        <div class="relative w-full">
                            <div class="rounded-xl overflow-hidden shadow-2xl">
                                <img
                                        src="{{ $project->featuredImage->webUrl }}"
                                        alt="{{ $project->featuredImage->alt ?? $project->title }}"
                                        class="w-full h-auto"
                                        loading="eager"
                                >
                            </div>
                        </div>
                    @else
                        <!-- Placeholder when no featured image -->
                        <div class="w-full aspect-video bg-gradient-to-br from-portfolio-accent/20 to-portfolio-accent/5 rounded-xl flex items-center justify-center">
                            <svg class="w-24 h-24 text-portfolio-accent/30" fill="none" stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                            </svg>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Project Description -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-6 lg:px-12">
            <div class="max-w-4xl mx-auto">
                <div class="prose prose-lg max-w-none text-portfolio-text/90">
                    {!! Str::markdown($project->description) !!}
                </div>
            </div>
        </div>
    </section>

    <!-- Gallery -->
    @if(count($project->galleryImages) > 0)
        <section class="py-16 bg-portfolio-light">
            <div class="container mx-auto px-6 lg:px-12">
                <div class="max-w-6xl mx-auto space-y-8">
                    <!-- Section Title -->
                    <div class="text-center">
                        <h2 class="text-2xl md:text-3xl font-bold text-portfolio-dark">project gallery</h2>
                        <div class="mt-2 h-1 w-20 bg-portfolio-accent mx-auto rounded-full"></div>
                    </div>

                    <!-- Gallery Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($project->galleryImages as $image)
                            <div class="group rounded-xl overflow-hidden shadow-lg hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">
                                <div class="aspect-video overflow-hidden bg-portfolio-secondary">
                                    <img
                                            src="{{ $image->thumbUrl }}"
                                            alt="{{ $image->alt ?? $project->title }}"
                                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                            loading="lazy"
                                    >
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- Divider -->
    <div class="h-px bg-gradient-to-r from-transparent via-portfolio-accent/30 to-transparent"></div>

    <!-- Back to Projects CTA -->
    <section class="py-20 bg-gradient-to-b from-portfolio-light to-portfolio-secondary">
        <div class="container mx-auto px-6 lg:px-12 text-center">
            <div class="max-w-3xl mx-auto space-y-6">
                <h2 class="text-3xl md:text-4xl font-bold text-portfolio-dark">explore more projects</h2>
                <p class="text-lg text-portfolio-text/80">discover other audio engineering work from our portfolio.</p>
                <div class="pt-4">
                    <x-portfolio.cta-link href="{{ route('projects') }}" :primary="true">
                        back to projects
                    </x-portfolio.cta-link>
                </div>
            </div>
        </div>
    </section>
@endsection
