<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $seo['title'] ?? 'Soundscape Audio' }}</title>
    <meta name="description" content="{{ $seo['description'] ?? 'Professional audio engineering services' }}">
    <meta name="keywords" content="{{ $seo['keywords'] ?? 'audio engineering, mixing, mastering' }}">
    
    <!-- Open Graph -->
    <meta property="og:title" content="{{ $seo['title'] ?? 'Soundscape Audio' }}">
    <meta property="og:description" content="{{ $seo['description'] ?? 'Professional audio engineering services' }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $seo['title'] ?? 'Soundscape Audio' }}">
    <meta name="twitter:description" content="{{ $seo['description'] ?? 'Professional audio engineering services' }}">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-white pt-20">
    <livewire:components.navbar />
    
    <main>
        @yield('content')
    </main>
    
    <livewire:components.footer />
</body>
</html>