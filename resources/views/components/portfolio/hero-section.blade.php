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
                                {{ $link['text'] ?? __('portfolio.cta.learn_more') }}
                            </x-portfolio.cta-link>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Illustration -->
            @if($showIllustration)
                <div class="flex justify-center lg:justify-end">
                    <div class="relative w-full max-w-md flex items-center justify-center">
                        <!-- Cercle décoratif en arrière-plan -->
                        <div class="absolute inset-0 bg-gradient-to-br from-portfolio-accent/20 to-portfolio-primary/10 rounded-full blur-3xl"></div>

                        <!-- Logo animé avec rotation des méridiens -->
                        <x-app-logo-animated class="w-64 h-64 md:w-80 md:h-80 lg:w-96 lg:h-96 text-portfolio-dark/80 drop-shadow-lg" />
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>
