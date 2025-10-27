<div>
    @if (session()->has('success'))
        <div class="mb-6 bg-portfolio-accent/10 border-l-4 border-portfolio-accent text-portfolio-dark px-6 py-4 rounded-sm">
            <div class="flex items-start gap-3">
                <span class="text-portfolio-accent text-xl font-bold">✓</span>
                <p class="text-portfolio-dark">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if ($submitted)
        <div class="bg-portfolio-accent/10 border-l-4 border-portfolio-accent text-portfolio-dark px-6 py-4 rounded-sm">
            <div class="flex items-start gap-3">
                <span class="text-portfolio-accent text-xl font-bold">✓</span>
                <div>
                    <p class="font-semibold text-portfolio-dark">Thank you for your message!</p>
                    <p class="text-portfolio-text/70 text-sm mt-1">We will get back to you soon.</p>
                </div>
            </div>
        </div>
    @else
        <form wire:submit="submit" class="space-y-6">
            <div>
                <label for="name" class="flex items-center gap-2 text-sm font-medium text-portfolio-dark mb-2">
                    <span class="text-portfolio-accent">></span>
                    Name *
                </label>
                <input
                    type="text"
                    wire:model="name"
                    id="name"
                    class="w-full px-4 py-3 border border-portfolio-accent/20 rounded-sm bg-white/50 focus:ring-2 focus:ring-portfolio-accent focus:border-portfolio-accent transition-all duration-200 @error('name') border-portfolio-accent-dark ring-1 ring-portfolio-accent-dark/20 @enderror"
                >
                @error('name')
                    <p class="mt-2 text-sm text-portfolio-accent-dark flex items-start gap-1">
                        <span>⚠</span>
                        <span>{{ $message }}</span>
                    </p>
                @enderror
            </div>

            <div>
                <label for="email" class="flex items-center gap-2 text-sm font-medium text-portfolio-dark mb-2">
                    <span class="text-portfolio-accent">></span>
                    Email *
                </label>
                <input
                    type="email"
                    wire:model="email"
                    id="email"
                    class="w-full px-4 py-3 border border-portfolio-accent/20 rounded-sm bg-white/50 focus:ring-2 focus:ring-portfolio-accent focus:border-portfolio-accent transition-all duration-200 @error('email') border-portfolio-accent-dark ring-1 ring-portfolio-accent-dark/20 @enderror"
                >
                @error('email')
                    <p class="mt-2 text-sm text-portfolio-accent-dark flex items-start gap-1">
                        <span>⚠</span>
                        <span>{{ $message }}</span>
                    </p>
                @enderror
            </div>

            <div>
                <label for="subject" class="flex items-center gap-2 text-sm font-medium text-portfolio-dark mb-2">
                    <span class="text-portfolio-accent">></span>
                    Subject
                </label>
                <input
                    type="text"
                    wire:model="subject"
                    id="subject"
                    class="w-full px-4 py-3 border border-portfolio-accent/20 rounded-sm bg-white/50 focus:ring-2 focus:ring-portfolio-accent focus:border-portfolio-accent transition-all duration-200 @error('subject') border-portfolio-accent-dark ring-1 ring-portfolio-accent-dark/20 @enderror"
                >
                @error('subject')
                    <p class="mt-2 text-sm text-portfolio-accent-dark flex items-start gap-1">
                        <span>⚠</span>
                        <span>{{ $message }}</span>
                    </p>
                @enderror
            </div>

            <div>
                <label for="message" class="flex items-center gap-2 text-sm font-medium text-portfolio-dark mb-2">
                    <span class="text-portfolio-accent">></span>
                    Message *
                </label>
                <textarea
                    wire:model="message"
                    id="message"
                    rows="5"
                    class="w-full px-4 py-3 border border-portfolio-accent/20 rounded-sm bg-white/50 focus:ring-2 focus:ring-portfolio-accent focus:border-portfolio-accent transition-all duration-200 resize-none @error('message') border-portfolio-accent-dark ring-1 ring-portfolio-accent-dark/20 @enderror"
                ></textarea>
                @error('message')
                    <p class="mt-2 text-sm text-portfolio-accent-dark flex items-start gap-1">
                        <span>⚠</span>
                        <span>{{ $message }}</span>
                    </p>
                @enderror
            </div>

            <div class="flex items-start">
                <input
                    type="checkbox"
                    wire:model="gdpr_consent"
                    id="gdpr_consent"
                    class="mt-1 h-4 w-4 text-portfolio-accent border-portfolio-accent/30 rounded-sm focus:ring-2 focus:ring-portfolio-accent focus:ring-offset-0 transition-colors duration-200 @error('gdpr_consent') border-portfolio-accent-dark ring-1 ring-portfolio-accent-dark/20 @enderror"
                >
                <label for="gdpr_consent" class="ml-3 text-sm text-portfolio-text/80">
                    I consent to the processing of my personal data in accordance with the privacy policy. *
                </label>
            </div>
            @error('gdpr_consent')
                <p class="mt-2 text-sm text-portfolio-accent-dark flex items-start gap-1">
                    <span>⚠</span>
                    <span>{{ $message }}</span>
                </p>
            @enderror

            <button
                type="submit"
                wire:loading.attr="disabled"
                class="w-full bg-portfolio-accent hover:bg-portfolio-accent-dark disabled:bg-portfolio-accent/50 disabled:cursor-not-allowed text-white font-semibold py-3 px-6 rounded-sm transition-all duration-200 hover:shadow-md"
            >
                <span wire:loading.remove>Send Message</span>
                <span wire:loading>Sending...</span>
            </button>
        </form>
    @endif
</div>