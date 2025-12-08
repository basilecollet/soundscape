<footer class="bg-portfolio-dark text-portfolio-light py-12 mt-20">
    <div class="container mx-auto px-6 lg:px-12">
        <div class="flex flex-col md:flex-row justify-between items-center space-y-6 md:space-y-0">
            <!-- Links Section -->
            <div class="flex flex-wrap justify-center md:justify-start gap-6 text-sm">
                <a href="#" class="hover:text-portfolio-primary transition-colors duration-200 flex items-center gap-1">
                    <span class="text-portfolio-accent">></span> {{ __('portfolio.footer.mailing_list') }}
                </a>
                <a href="#" class="hover:text-portfolio-primary transition-colors duration-200 flex items-center gap-1">
                    <span class="text-portfolio-accent">></span> {{ __('portfolio.footer.instagram') }}
                </a>
                <a href="#" class="hover:text-portfolio-primary transition-colors duration-200 flex items-center gap-1">
                    <span class="text-portfolio-accent">></span> {{ __('portfolio.footer.legal') }}
                </a>
            </div>
            
            <!-- Copyright -->
            <div class="text-sm text-portfolio-light/70">
                {{ date('Y') }} - soundscapeaudio.store
            </div>
        </div>
    </div>
</footer>