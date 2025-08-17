@extends('layouts.portfolio')

@section('content')
<div class="bg-gradient-to-br from-slate-900 to-slate-800 text-white">
    <!-- Hero Section -->
    <section class="py-20 pt-32 px-4 text-center">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-5xl font-bold mb-6">{{ $content['hero_title'] }}</h1>
            <p class="text-xl text-slate-300 mb-8">{{ $content['hero_subtitle'] }}</p>
            <p class="text-lg text-slate-400 max-w-2xl mx-auto">{{ $content['hero_text'] }}</p>
        </div>
    </section>
</div>

<!-- Features Section -->
<section class="py-16 px-4 bg-gray-50">
    <div class="max-w-6xl mx-auto">
        <div class="grid md:grid-cols-3 gap-8">
            @foreach($content['features'] as $feature)
            <div class="bg-white p-8 rounded-lg shadow-md text-center">
                <h3 class="text-xl font-semibold mb-4 text-slate-800">{{ $feature['title'] }}</h3>
                <p class="text-slate-600">{{ $feature['description'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 px-4 bg-slate-800 text-white text-center">
    <div class="max-w-4xl mx-auto">
        <h2 class="text-3xl font-bold mb-6">Ready to Start Your Project?</h2>
        <p class="text-xl text-slate-300 mb-8">Let's discuss how we can bring your audio vision to life.</p>
        <a href="{{ route('contact') }}" class="inline-block bg-blue-600 hover:bg-blue-700 px-8 py-3 rounded-lg font-semibold transition-colors">
            Get in Touch
        </a>
    </div>
</section>
@endsection