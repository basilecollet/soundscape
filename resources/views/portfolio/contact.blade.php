@extends('layouts.portfolio')

@section('content')
    <!-- Hero Section -->
    <section class="pt-32 pb-20 bg-gradient-to-b from-portfolio-secondary to-portfolio-light">
        <div class="container mx-auto px-6 lg:px-12">
            <div class="max-w-4xl">
                <h1 class="text-4xl md:text-5xl font-bold text-portfolio-dark mb-6">{{ $content['title'] }}</h1>
                <p class="text-lg md:text-xl text-portfolio-text/80 mb-4">{{ $content['subtitle'] }}</p>
                <p class="text-base md:text-lg text-portfolio-text/70 leading-relaxed">{{ $content['description'] }}</p>
            </div>
        </div>
    </section>

    <!-- Contact Content -->
    <section class="py-20 bg-portfolio-light">
        <div class="container mx-auto px-6 lg:px-12">
            <div class="grid lg:grid-cols-2 gap-16 max-w-6xl">
                <!-- Contact Information -->
                <div class="space-y-8">
                    <div>
                        <h2 class="text-2xl font-bold mb-8 text-portfolio-dark">contact information</h2>
                        <div class="space-y-6">
                            <div class="flex items-start gap-3">
                                <span class="text-portfolio-accent mt-1">></span>
                                <div>
                                    <p class="text-sm text-portfolio-text/60 mb-1">email</p>
                                    <a href="mailto:{{ $content['info']['email'] }}" class="text-portfolio-text hover:text-portfolio-accent transition-colors">
                                        {{ $content['info']['email'] }}
                                    </a>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="text-portfolio-accent mt-1">></span>
                                <div>
                                    <p class="text-sm text-portfolio-text/60 mb-1">phone</p>
                                    <a href="tel:{{ $content['info']['phone'] }}" class="text-portfolio-text hover:text-portfolio-accent transition-colors">
                                        {{ $content['info']['phone'] }}
                                    </a>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="text-portfolio-accent mt-1">></span>
                                <div>
                                    <p class="text-sm text-portfolio-text/60 mb-1">location</p>
                                    <p class="text-portfolio-text">{{ $content['info']['location'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="bg-portfolio-secondary p-6 md:p-8 lg:p-10 rounded-sm">
                    <h2 class="text-2xl font-bold mb-8 text-portfolio-dark">send a message</h2>
                    <livewire:contact-form />
                </div>
            </div>
        </div>
    </section>
@endsection
