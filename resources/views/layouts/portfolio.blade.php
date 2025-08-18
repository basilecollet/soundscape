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
    
    <!-- Google Fonts - Kode Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kode+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="min-h-screen bg-portfolio-light font-mono text-portfolio-text">
    <x-portfolio.navbar />
    
    <main>
        @yield('content')
    </main>
    
    <x-portfolio.footer />
</body>
</html>