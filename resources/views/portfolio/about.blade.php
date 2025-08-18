@extends('layouts.portfolio')

@section('content')
    <!-- Hero Section -->
    <section class="pt-32 pb-20 bg-gradient-to-b from-portfolio-secondary to-portfolio-light">
        <div class="container mx-auto px-6 lg:px-12">
            <div class="max-w-4xl">
                <h1 class="text-4xl md:text-5xl font-bold text-portfolio-dark mb-6">{{ $content['title'] }}</h1>
                <p class="text-lg md:text-xl text-portfolio-text/80 leading-relaxed">{{ $content['intro'] }}</p>
            </div>
        </div>
    </section>

    <!-- Bio Section -->
    <section class="py-20 bg-portfolio-light">
        <div class="container mx-auto px-6 lg:px-12">
            <div class="max-w-4xl">
                <p class="text-base md:text-lg text-portfolio-text/70 leading-relaxed">{{ $content['bio'] }}</p>
            </div>
        </div>
    </section>

    <!-- Experience Stats -->
    <section class="py-20 bg-portfolio-secondary">
        <div class="container mx-auto px-6 lg:px-12">
            <div class="grid md:grid-cols-3 gap-8 md:gap-12">
                <div class="text-center space-y-2">
                    <div class="text-4xl md:text-5xl font-bold text-portfolio-accent">{{ $content['experience']['years'] }}</div>
                    <div class="text-sm text-portfolio-text/70">years of experience</div>
                </div>
                <div class="text-center space-y-2">
                    <div class="text-4xl md:text-5xl font-bold text-portfolio-accent">{{ $content['experience']['projects'] }}</div>
                    <div class="text-sm text-portfolio-text/70">projects completed</div>
                </div>
                <div class="text-center space-y-2">
                    <div class="text-4xl md:text-5xl font-bold text-portfolio-accent">{{ $content['experience']['clients'] }}</div>
                    <div class="text-sm text-portfolio-text/70">happy clients</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services -->
    <section class="py-20 bg-portfolio-light">
        <div class="container mx-auto px-6 lg:px-12">
            <h2 class="text-2xl md:text-3xl font-bold text-center mb-12 text-portfolio-dark">services</h2>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4 max-w-4xl mx-auto">
                @foreach($content['services'] as $service)
                <div class="flex items-center gap-2 group">
                    <span class="text-portfolio-accent">></span>
                    <span class="text-portfolio-text/80 text-sm">{{ $service }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Philosophy -->
    <section class="py-20 bg-portfolio-dark text-portfolio-light">
        <div class="container mx-auto px-6 lg:px-12">
            <div class="max-w-4xl mx-auto text-center space-y-6">
                <h2 class="text-2xl md:text-3xl font-bold">our philosophy</h2>
                <p class="text-base md:text-lg text-portfolio-light/80 leading-relaxed">{{ $content['philosophy'] }}</p>
            </div>
        </div>
    </section>
@endsection