<nav class="fixed top-0 left-0 w-full z-50 bg-transparent mix-blend-difference">
    <div class="container mx-auto px-6 lg:px-12 py-6">
        <div class="flex justify-between items-center">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="text-portfolio-dark font-semibold text-lg tracking-tight hover:text-portfolio-accent transition-colors duration-200">
                soundscape
            </a>
            
            <!-- Navigation Links - Desktop -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="{{ route('home') }}" 
                   class="text-portfolio-dark hover:text-portfolio-accent transition-colors duration-200 text-sm {{ request()->routeIs('home') ? 'text-portfolio-accent' : '' }}">
                    home
                </a>
                <a href="{{ route('about') }}" 
                   class="text-portfolio-dark hover:text-portfolio-accent transition-colors duration-200 text-sm {{ request()->routeIs('about') ? 'text-portfolio-accent' : '' }}">
                    about
                </a>
                <a href="{{ route('contact') }}" 
                   class="text-portfolio-dark hover:text-portfolio-accent transition-colors duration-200 text-sm {{ request()->routeIs('contact') ? 'text-portfolio-accent' : '' }}">
                    contact
                </a>
            </div>
            
            <!-- Mobile Menu Button -->
            <button 
                x-data="{ open: false }"
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
        
        <!-- Mobile Navigation -->
        <div 
            x-data="{ open: false }"
            x-show="open"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-4"
            @click.away="open = false"
            class="md:hidden absolute top-full left-0 w-full bg-portfolio-light border-t border-portfolio-dark/10 shadow-lg"
        >
            <div class="container mx-auto px-6 py-6 space-y-4">
                <a href="{{ route('home') }}" 
                   class="block text-portfolio-dark hover:text-portfolio-accent transition-colors duration-200 text-sm {{ request()->routeIs('home') ? 'text-portfolio-accent' : '' }}">
                    home
                </a>
                <a href="{{ route('about') }}" 
                   class="block text-portfolio-dark hover:text-portfolio-accent transition-colors duration-200 text-sm {{ request()->routeIs('about') ? 'text-portfolio-accent' : '' }}">
                    about
                </a>
                <a href="{{ route('contact') }}" 
                   class="block text-portfolio-dark hover:text-portfolio-accent transition-colors duration-200 text-sm {{ request()->routeIs('contact') ? 'text-portfolio-accent' : '' }}">
                    contact
                </a>
            </div>
        </div>
    </div>
</nav>