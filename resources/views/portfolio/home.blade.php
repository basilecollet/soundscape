@extends('layouts.portfolio')

@section('content')
    <!-- Hero Section -->
    <x-portfolio.hero-section
        :title="$content['hero_title']"
        :subtitle="$content['hero_subtitle']"
        :description="$content['hero_text']"
        :showIllustration="true"
        :ctaLinks="[
            ['text' => __('portfolio.home.cta.explore_work'), 'href' => route('about'), 'primary' => true],
            ['text' => __('portfolio.home.cta.get_in_touch'), 'href' => route('contact'), 'primary' => false]
        ]"
    />

    @if($content['show_features'])
    <!-- Features Section -->
    <section class="py-20 bg-portfolio-light">
        <div class="container mx-auto px-6 lg:px-12">
            <div class="grid md:grid-cols-3 gap-8 md:gap-12">
                @foreach($content['features'] as $feature)
                <div class="space-y-4 group">
                    <div class="flex items-start gap-3">
                        <span class="text-portfolio-accent text-lg mt-1">></span>
                        <div>
                            <h3 class="text-lg font-semibold text-portfolio-dark mb-2">{{ $feature['title'] }}</h3>
                            <p class="text-portfolio-text/70 text-sm leading-relaxed">{{ $feature['description'] }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @if($content['show_features'] && $content['show_cta'])
    <!-- Divider -->
    <div class="h-px bg-gradient-to-r from-transparent via-portfolio-accent/30 to-transparent"></div>
    @endif

    @if($content['show_cta'])
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
    @endif
@endsection