@extends('layouts.portfolio')

@section('content')
    <section class="min-h-screen flex items-center justify-center bg-gradient-to-b from-portfolio-secondary to-portfolio-light py-20">
        <div class="container mx-auto px-6 lg:px-12">
            <div class="max-w-2xl mx-auto text-center space-y-8">
                <!-- Icon -->
                <div class="flex justify-center">
                    <svg class="w-20 h-20 text-portfolio-accent/40" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>

                <!-- Title -->
                <h1 class="text-3xl md:text-4xl font-bold text-portfolio-dark">
                    {{ $title }}
                </h1>

                <!-- Description -->
                <p class="text-lg text-portfolio-text/70 leading-relaxed">
                    {{ $description }}
                </p>

                <!-- Decorative divider -->
                <div class="pt-4">
                    <div class="h-px bg-gradient-to-r from-transparent via-portfolio-accent/30 to-transparent"></div>
                </div>
            </div>
        </div>
    </section>
@endsection
