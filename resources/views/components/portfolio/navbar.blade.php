<nav 
    x-data="{ 
        open: false,
        scrolled: false,
        handleScroll() {
            this.scrolled = window.scrollY > 50;
        }
    }"
    x-init="
        handleScroll();
        window.addEventListener('scroll', () => handleScroll());
    "
    class="fixed z-50 transition-all duration-300"
    :class="{
        'md:top-4 md:left-4 md:right-4 md:w-auto top-0 left-0 w-full': scrolled,
        'top-0 left-0 w-full': !scrolled
    }"
>
    <div 
        class="transition-all duration-300"
        :class="{
            'bg-white shadow-lg md:rounded-full': scrolled || open,
            'bg-transparent': !scrolled && !open
        }"
    >
        <div class="container mx-auto px-6 lg:px-12 py-6">
            <div class="flex justify-between items-center">
            <!-- Logo -->
            <a href="{{ route('home') }}" 
               class="flex items-center transition-all duration-200 hover:scale-105">
                <img 
                    src="{{ asset('images/logos/logo.svg') }}" 
                    alt="Soundscape Audio" 
                    class="h-8 w-auto brightness-0"
                >
            </a>
            
            <!-- Navigation Links - Desktop -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="{{ route('home') }}"
                   class="relative transition-colors duration-200 text-sm py-1"
                   :class="{
                       'text-portfolio-dark hover:text-portfolio-accent': scrolled || open,
                       'text-portfolio-dark hover:text-portfolio-accent mix-blend-difference': !scrolled && !open
                   }">
                    {{ __('portfolio.navigation.home') }}
                    @if(request()->routeIs('home'))
                        <span class="absolute -bottom-1 left-0 right-0 h-[2px] bg-portfolio-accent rounded-full"></span>
                    @endif
                </a>
                <a href="{{ route('about') }}"
                   class="relative transition-colors duration-200 text-sm py-1"
                   :class="{
                       'text-portfolio-dark hover:text-portfolio-accent': scrolled || open,
                       'text-portfolio-dark hover:text-portfolio-accent mix-blend-difference': !scrolled && !open
                   }">
                    {{ __('portfolio.navigation.about') }}
                    @if(request()->routeIs('about'))
                        <span class="absolute -bottom-1 left-0 right-0 h-[2px] bg-portfolio-accent rounded-full"></span>
                    @endif
                </a>
                <a href="{{ route('projects') }}"
                   class="relative transition-colors duration-200 text-sm py-1"
                   :class="{
                       'text-portfolio-dark hover:text-portfolio-accent': scrolled || open,
                       'text-portfolio-dark hover:text-portfolio-accent mix-blend-difference': !scrolled && !open
                   }">
                    {{ __('portfolio.navigation.projects') }}
                    @if(request()->routeIs('projects'))
                        <span class="absolute -bottom-1 left-0 right-0 h-[2px] bg-portfolio-accent rounded-full"></span>
                    @endif
                </a>
                <a href="{{ route('contact') }}"
                   class="relative transition-colors duration-200 text-sm py-1"
                   :class="{
                       'text-portfolio-dark hover:text-portfolio-accent': scrolled || open,
                       'text-portfolio-dark hover:text-portfolio-accent mix-blend-difference': !scrolled && !open
                   }">
                    {{ __('portfolio.navigation.contact') }}
                    @if(request()->routeIs('contact'))
                        <span class="absolute -bottom-1 left-0 right-0 h-[2px] bg-portfolio-accent rounded-full"></span>
                    @endif
                </a>
            </div>
            
            <!-- Mobile Menu Button -->
            <button 
                @click="open = !open"
                class="md:hidden flex flex-col justify-center items-center w-8 h-8 space-y-1.5 focus:outline-none"
            >
                <span 
                    class="block w-6 h-[1.5px] bg-portfolio-dark transition-all duration-300"
                    :class="{ 'rotate-45 translate-y-[7.5px]': open }"
                ></span>
                <span 
                    class="block w-6 h-[1.5px] bg-portfolio-dark transition-all duration-300"
                    :class="{ 'opacity-0': open }"
                ></span>
                <span 
                    class="block w-6 h-[1.5px] bg-portfolio-dark transition-all duration-300"
                    :class="{ '-rotate-45 -translate-y-[7.5px]': open }"
                ></span>
            </button>
            </div>
        </div>
        
        <!-- Mobile Navigation -->
        <div 
            x-show="open"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-4"
            @click.away="open = false"
            class="md:hidden mt-2 bg-white border-t border-portfolio-dark/10 shadow-lg"
        >
            <div class="container mx-auto px-6 py-6 space-y-4">
                <a href="{{ route('home') }}"
                   @click="open = false"
                   class="flex items-center gap-2 text-portfolio-dark hover:text-portfolio-accent transition-colors duration-200 text-sm">
                    @if(request()->routeIs('home'))
                        <span class="w-2 h-2 bg-portfolio-accent rounded-full"></span>
                    @else
                        <span class="w-2 h-2"></span>
                    @endif
                    {{ __('portfolio.navigation.home') }}
                </a>
                <a href="{{ route('about') }}"
                   @click="open = false"
                   class="flex items-center gap-2 text-portfolio-dark hover:text-portfolio-accent transition-colors duration-200 text-sm">
                    @if(request()->routeIs('about'))
                        <span class="w-2 h-2 bg-portfolio-accent rounded-full"></span>
                    @else
                        <span class="w-2 h-2"></span>
                    @endif
                    {{ __('portfolio.navigation.about') }}
                </a>
                <a href="{{ route('projects') }}"
                   @click="open = false"
                   class="flex items-center gap-2 text-portfolio-dark hover:text-portfolio-accent transition-colors duration-200 text-sm">
                    @if(request()->routeIs('projects'))
                        <span class="w-2 h-2 bg-portfolio-accent rounded-full"></span>
                    @else
                        <span class="w-2 h-2"></span>
                    @endif
                    {{ __('portfolio.navigation.projects') }}
                </a>
                <a href="{{ route('contact') }}"
                   @click="open = false"
                   class="flex items-center gap-2 text-portfolio-dark hover:text-portfolio-accent transition-colors duration-200 text-sm">
                    @if(request()->routeIs('contact'))
                        <span class="w-2 h-2 bg-portfolio-accent rounded-full"></span>
                    @else
                        <span class="w-2 h-2"></span>
                    @endif
                    {{ __('portfolio.navigation.contact') }}
                </a>
            </div>
        </div>
    </div>
</nav>