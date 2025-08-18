@props([
    'title' => '',
    'subtitle' => '',
    'description' => '',
    'showIllustration' => true,
    'ctaLinks' => []
])

<section class="min-h-screen flex items-center pt-20 pb-12 bg-gradient-to-b from-portfolio-secondary via-portfolio-light to-portfolio-accent/10">
    <div class="container mx-auto px-6 lg:px-12">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <!-- Content -->
            <div class="space-y-8">
                @if($title)
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-portfolio-dark leading-tight">
                        {{ $title }}
                    </h1>
                @endif
                
                @if($subtitle)
                    <p class="text-lg md:text-xl text-portfolio-text/80 font-medium">
                        {{ $subtitle }}
                    </p>
                @endif
                
                @if($description)
                    <p class="text-base md:text-lg text-portfolio-text/70 leading-relaxed max-w-xl">
                        {{ $description }}
                    </p>
                @endif
                
                @if(count($ctaLinks) > 0)
                    <div class="flex flex-col sm:flex-row gap-4 pt-4">
                        @foreach($ctaLinks as $link)
                            <x-portfolio.cta-link 
                                :href="$link['href'] ?? '#'" 
                                :primary="$link['primary'] ?? false"
                            >
                                {{ $link['text'] ?? 'Learn More' }}
                            </x-portfolio.cta-link>
                        @endforeach
                    </div>
                @endif
            </div>
            
            <!-- Illustration -->
            @if($showIllustration)
                <div class="flex justify-center lg:justify-end">
                    <div class="relative w-full max-w-md">
                        <!-- Wireframe Globe SVG -->
                        <svg viewBox="0 0 400 400" class="w-full h-auto" xmlns="http://www.w3.org/2000/svg">
                            <!-- Outer circle -->
                            <circle cx="200" cy="200" r="150" 
                                    stroke="currentColor" 
                                    stroke-width="1.5" 
                                    fill="none" 
                                    class="text-portfolio-accent" />
                            
                            <!-- Horizontal lines (latitude) -->
                            <ellipse cx="200" cy="200" rx="150" ry="50" 
                                     stroke="currentColor" 
                                     stroke-width="1" 
                                     fill="none" 
                                     class="text-portfolio-accent/50" />
                            <ellipse cx="200" cy="200" rx="150" ry="100" 
                                     stroke="currentColor" 
                                     stroke-width="1" 
                                     fill="none" 
                                     class="text-portfolio-accent/50" />
                            
                            <!-- Vertical lines (longitude) -->
                            <ellipse cx="200" cy="200" rx="50" ry="150" 
                                     stroke="currentColor" 
                                     stroke-width="1" 
                                     fill="none" 
                                     class="text-portfolio-accent/50" />
                            <ellipse cx="200" cy="200" rx="100" ry="150" 
                                     stroke="currentColor" 
                                     stroke-width="1" 
                                     fill="none" 
                                     class="text-portfolio-accent/50" />
                            
                            <!-- Center lines -->
                            <line x1="50" y1="200" x2="350" y2="200" 
                                  stroke="currentColor" 
                                  stroke-width="1" 
                                  class="text-portfolio-accent/70" />
                            <line x1="200" y1="50" x2="200" y2="350" 
                                  stroke="currentColor" 
                                  stroke-width="1" 
                                  class="text-portfolio-accent/70" />
                        </svg>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>