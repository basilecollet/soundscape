@extends('layouts.portfolio')

@section('content')
<div class="bg-gradient-to-br from-slate-900 to-slate-800 text-white">
    <!-- Hero Section -->
    <section class="py-20 px-4 text-center">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-5xl font-bold mb-6">{{ $content['title'] }}</h1>
            <p class="text-xl text-slate-300 mb-4">{{ $content['subtitle'] }}</p>
            <p class="text-lg text-slate-400 max-w-2xl mx-auto">{{ $content['description'] }}</p>
        </div>
    </section>
</div>

<!-- Contact Content -->
<section class="py-16 px-4">
    <div class="max-w-6xl mx-auto">
        <div class="grid lg:grid-cols-2 gap-12">
            <!-- Contact Information -->
            <div class="space-y-8">
                <div>
                    <h2 class="text-2xl font-bold mb-6 text-slate-800">Contact Information</h2>
                    <div class="space-y-4">
                        <div class="flex items-center space-x-4">
                            <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center">
                                <span class="text-blue-600 text-sm">@</span>
                            </div>
                            <span class="text-slate-600">{{ $content['info']['email'] }}</span>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center">
                                <span class="text-blue-600 text-sm">📞</span>
                            </div>
                            <span class="text-slate-600">{{ $content['info']['phone'] }}</span>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center">
                                <span class="text-blue-600 text-sm">📍</span>
                            </div>
                            <span class="text-slate-600">{{ $content['info']['location'] }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="bg-gray-50 p-8 rounded-lg">
                <h2 class="text-2xl font-bold mb-6 text-slate-800">Send a Message</h2>
                <livewire:contact-form />
            </div>
        </div>
    </div>
</section>
@endsection