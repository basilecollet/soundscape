@extends('layouts.portfolio')

@section('content')
    <!-- Hero Section -->
    <section class="py-20 bg-gradient-to-b from-portfolio-secondary via-portfolio-light to-white">
        <div class="container mx-auto px-6 lg:px-12">
            <div class="max-w-3xl mx-auto text-center space-y-6">
                <h1 class="text-4xl md:text-5xl font-bold text-portfolio-dark">our projects</h1>
                <p class="text-lg text-portfolio-text/80">explore our portfolio of professional audio engineering work</p>
            </div>
        </div>
    </section>

    <!-- Projects Grid Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-6 lg:px-12">
            @if($projects->isNotEmpty())
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($projects as $project)
                        <div class="bg-portfolio-light rounded-xl p-6 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                            <h3 class="text-xl font-semibold text-portfolio-dark mb-2">{{ $project->title }}</h3>
                            @if($project->shortDescription)
                                <p class="text-sm text-portfolio-text/70 mb-4">{{ $project->shortDescription }}</p>
                            @endif
                            @if($project->projectDate)
                                <p class="text-xs text-portfolio-accent">{{ $project->projectDate }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <p class="text-portfolio-text/70">no projects available at the moment</p>
                </div>
            @endif
        </div>
    </section>
@endsection