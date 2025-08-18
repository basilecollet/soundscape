@extends('layouts.portfolio')

@section('content')
    <!-- Hero Section -->
    <x-portfolio.hero-section
        :title="$content['hero_title']"
        :subtitle="$content['hero_subtitle']"
        :description="$content['hero_text']"
        :showIllustration="true"
        :ctaLinks="[
            ['text' => 'explore our work', 'href' => route('about'), 'primary' => true],
            ['text' => 'get in touch', 'href' => route('contact'), 'primary' => false]
        ]"
    />

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

    <!-- Divider -->
    <div class="h-px bg-gradient-to-r from-transparent via-portfolio-accent/30 to-transparent"></div>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-b from-portfolio-light to-portfolio-secondary">
        <div class="container mx-auto px-6 lg:px-12 text-center">
            <div class="max-w-3xl mx-auto space-y-6">
                <h2 class="text-3xl md:text-4xl font-bold text-portfolio-dark">ready to start your project?</h2>
                <p class="text-lg text-portfolio-text/80">let's discuss how we can bring your audio vision to life.</p>
                <div class="pt-4">
                    <x-portfolio.cta-link href="{{ route('contact') }}" :primary="true">
                        get in touch
                    </x-portfolio.cta-link>
                </div>
            </div>
        </div>
    </section>
@endsection