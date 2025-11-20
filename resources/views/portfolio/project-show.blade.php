@extends('layouts.portfolio')

@section('content')
    <!-- Breadcrumb Navigation -->
    <section class="py-6 bg-white border-b border-portfolio-accent/10">
        <div class="container mx-auto px-6 lg:px-12">
            <nav class="flex items-center gap-2 text-sm text-portfolio-text/60">
                <a href="{{ route('home') }}" class="hover:text-portfolio-accent transition-colors duration-200">
                    home
                </a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <a href="{{ route('projects') }}" class="hover:text-portfolio-accent transition-colors duration-200">
                    projects
                </a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <span class="text-portfolio-accent font-medium">{{ $project->title }}</span>
            </nav>
        </div>
    </section>

    <!-- Project Header -->
    <section class="py-12 bg-white">
        <div class="container mx-auto px-6 lg:px-12">
            <div class="max-w-4xl mx-auto space-y-6">
                <!-- Title -->
                <h1 class="text-4xl md:text-5xl font-bold text-portfolio-dark">
                    {{ $project->title }}
                </h1>

                <!-- Meta information -->
                <div class="flex flex-wrap items-center gap-4 text-sm text-portfolio-text/70">
                    @if($project->projectDate)
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-portfolio-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <time datetime="{{ $project->projectDate }}">
                                {{ \Carbon\Carbon::parse($project->projectDate)->format('F Y') }}
                            </time>
                        </div>
                    @endif
                </div>

                <!-- Short Description -->
                @if($project->shortDescription)
                    <p class="text-xl text-portfolio-text/80 leading-relaxed">
                        {{ $project->shortDescription }}
                    </p>
                @endif
            </div>
        </div>
    </section>

    <!-- Featured Image -->
    @if($project->featuredImage)
        <section class="py-8 bg-portfolio-secondary">
            <div class="container mx-auto px-6 lg:px-12">
                <div class="max-w-5xl mx-auto">
                    <div class="rounded-xl overflow-hidden shadow-2xl">
                        <img
                            src="{{ $project->featuredImage->webUrl }}"
                            alt="{{ $project->featuredImage->alt ?? $project->title }}"
                            class="w-full h-auto"
                            loading="eager"
                        >
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- Project Description -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-6 lg:px-12">
            <div class="max-w-4xl mx-auto">
                <div class="prose prose-lg prose-portfolio max-w-none">
                    <div class="text-portfolio-text/90 leading-relaxed space-y-4">
                        {!! nl2br(e($project->description)) !!}
                    </div>
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