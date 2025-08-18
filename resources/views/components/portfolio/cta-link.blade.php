@props([
    'href' => '#',
    'primary' => false,
    'external' => false
])

@if($primary)
    <a href="{{ $href }}" 
       {{ $external ? 'target="_blank" rel="noopener noreferrer"' : '' }}
       class="inline-flex items-center gap-2 px-6 py-3 bg-portfolio-accent text-white rounded-full hover:bg-portfolio-accent-dark transition-all duration-200 hover:gap-3 group">
        <span class="text-sm font-medium">{{ $slot }}</span>
        <span class="text-lg group-hover:translate-x-0.5 transition-transform duration-200">></span>
    </a>
@else
    <a href="{{ $href }}" 
       {{ $external ? 'target="_blank" rel="noopener noreferrer"' : '' }}
       class="inline-flex items-center gap-2 text-portfolio-dark hover:text-portfolio-accent transition-all duration-200 hover:gap-3 group">
        <span class="text-portfolio-accent text-lg group-hover:translate-x-0.5 transition-transform duration-200">></span>
        <span class="text-sm font-medium border-b border-transparent hover:border-current">{{ $slot }}</span>
    </a>
@endif