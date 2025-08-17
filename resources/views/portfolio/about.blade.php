@extends('layouts.portfolio')

@section('content')
<div class="bg-gradient-to-br from-slate-900 to-slate-800 text-white">
    <!-- Hero Section -->
    <section class="py-20 pt-32 px-4 text-center">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-5xl font-bold mb-6">{{ $content['title'] }}</h1>
            <p class="text-xl text-slate-300 max-w-2xl mx-auto">{{ $content['intro'] }}</p>
        </div>
    </section>
</div>

<!-- Bio Section -->
<section class="py-16 px-4">
    <div class="max-w-4xl mx-auto">
        <div class="prose prose-lg mx-auto text-slate-600">
            <p>{{ $content['bio'] }}</p>
        </div>
    </div>
</section>

<!-- Experience Stats -->
<section class="py-16 px-4 bg-gray-50">
    <div class="max-w-6xl mx-auto">
        <div class="grid md:grid-cols-3 gap-8 text-center">
            <div class="bg-white p-8 rounded-lg shadow-md">
                <div class="text-4xl font-bold text-blue-600 mb-2">{{ $content['experience']['years'] }}</div>
                <div class="text-slate-600">Years of Experience</div>
            </div>
            <div class="bg-white p-8 rounded-lg shadow-md">
                <div class="text-4xl font-bold text-blue-600 mb-2">{{ $content['experience']['projects'] }}</div>
                <div class="text-slate-600">Projects Completed</div>
            </div>
            <div class="bg-white p-8 rounded-lg shadow-md">
                <div class="text-4xl font-bold text-blue-600 mb-2">{{ $content['experience']['clients'] }}</div>
                <div class="text-slate-600">Happy Clients</div>
            </div>
        </div>
    </div>
</section>

<!-- Services -->
<section class="py-16 px-4">
    <div class="max-w-4xl mx-auto">
        <h2 class="text-3xl font-bold text-center mb-12 text-slate-800">Services</h2>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($content['services'] as $service)
            <div class="bg-slate-100 px-6 py-4 rounded-lg text-center">
                <span class="text-slate-700 font-medium">{{ $service }}</span>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Philosophy -->
<section class="py-16 px-4 bg-slate-800 text-white">
    <div class="max-w-4xl mx-auto text-center">
        <h2 class="text-3xl font-bold mb-8">Our Philosophy</h2>
        <p class="text-xl text-slate-300">{{ $content['philosophy'] }}</p>
    </div>
</section>
@endsection