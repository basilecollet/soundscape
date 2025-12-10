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
                    <p class="font-semibold text-portfolio-dark">{{ __('portfolio.contact.form.success_title') }}</p>
                    <p class="text-portfolio-text/70 text-sm mt-1">{{ __('portfolio.contact.form.success_description') }}</p>
                </div>
            </div>
        </div>
    @else
        <form wire:submit="submit" class="space-y-6">
            <div>
                <label for="name" class="flex items-center gap-2 text-sm font-medium text-portfolio-dark mb-2">
                    <span class="text-portfolio-accent">></span>
                    {{ __('portfolio.contact.form.name_label') }}
                </label>
                <input
                    type="text"
                    wire:model="name"
                    id="name"
                    required
                    aria-required="true"
                    maxlength="255"
                    aria-describedby="name-hint name-error"
                    class="w-full px-4 py-3 border border-portfolio-accent/20 rounded-sm bg-white/50 focus:ring-2 focus:ring-portfolio-accent focus:border-portfolio-accent transition-all duration-200 @error('name') border-portfolio-error ring-1 ring-portfolio-error/20 @enderror"
                >
                <p id="name-hint" class="mt-1 text-xs text-portfolio-text/60">
                    {{ __('portfolio.contact.form.name_hint') }}
                </p>
                @error('name')
                    <p id="name-error" class="mt-2 text-sm text-portfolio-error flex items-start gap-1">
                        <span aria-hidden="true">⚠</span>
                        <span>{{ $message }}</span>
                    </p>
                @enderror
            </div>

            <div>
                <label for="email" class="flex items-center gap-2 text-sm font-medium text-portfolio-dark mb-2">
                    <span class="text-portfolio-accent">></span>
                    {{ __('portfolio.contact.form.email_label') }}
                </label>
                <input
                    type="email"
                    wire:model="email"
                    id="email"
                    required
                    aria-required="true"
                    maxlength="255"
                    aria-describedby="email-hint email-error"
                    class="w-full px-4 py-3 border border-portfolio-accent/20 rounded-sm bg-white/50 focus:ring-2 focus:ring-portfolio-accent focus:border-portfolio-accent transition-all duration-200 @error('email') border-portfolio-error ring-1 ring-portfolio-error/20 @enderror"
                >
                <p id="email-hint" class="mt-1 text-xs text-portfolio-text/60">
                    {{ __('portfolio.contact.form.email_hint') }}
                </p>
                @error('email')
                    <p id="email-error" class="mt-2 text-sm text-portfolio-error flex items-start gap-1">
                        <span aria-hidden="true">⚠</span>
                        <span>{{ $message }}</span>
                    </p>
                @enderror
            </div>

            <div>
                <label for="subject" class="flex items-center gap-2 text-sm font-medium text-portfolio-dark mb-2">
                    <span class="text-portfolio-accent">></span>
                    {{ __('portfolio.contact.form.subject_label') }}
                </label>
                <input
                    type="text"
                    wire:model="subject"
                    id="subject"
                    maxlength="255"
                    aria-describedby="subject-hint subject-error"
                    class="w-full px-4 py-3 border border-portfolio-accent/20 rounded-sm bg-white/50 focus:ring-2 focus:ring-portfolio-accent focus:border-portfolio-accent transition-all duration-200 @error('subject') border-portfolio-error ring-1 ring-portfolio-error/20 @enderror"
                >
                <p id="subject-hint" class="mt-1 text-xs text-portfolio-text/60">
                    {{ __('portfolio.contact.form.subject_hint') }}
                </p>
                @error('subject')
                    <p id="subject-error" class="mt-2 text-sm text-portfolio-error flex items-start gap-1">
                        <span aria-hidden="true">⚠</span>
                        <span>{{ $message }}</span>
                    </p>
                @enderror
            </div>

            <div>
                <label for="message" class="flex items-center gap-2 text-sm font-medium text-portfolio-dark mb-2">
                    <span class="text-portfolio-accent">></span>
                    {{ __('portfolio.contact.form.message_label') }}
                </label>
                <textarea
                    wire:model="message"
                    id="message"
                    rows="5"
                    required
                    aria-required="true"
                    maxlength="2000"
                    aria-describedby="message-hint message-error"
                    class="w-full px-4 py-3 border border-portfolio-accent/20 rounded-sm bg-white/50 focus:ring-2 focus:ring-portfolio-accent focus:border-portfolio-accent transition-all duration-200 resize-none @error('message') border-portfolio-error ring-1 ring-portfolio-error/20 @enderror"
                ></textarea>
                <p id="message-hint" class="mt-1 text-xs text-portfolio-text/60">
                    {{ __('portfolio.contact.form.message_hint') }}
                </p>
                @error('message')
                    <p id="message-error" class="mt-2 text-sm text-portfolio-error flex items-start gap-1">
                        <span aria-hidden="true">⚠</span>
                        <span>{{ $message }}</span>
                    </p>
                @enderror
            </div>

            <div class="flex items-start">
                <input
                    type="checkbox"
                    wire:model="gdpr_consent"
                    id="gdpr_consent"
                    required
                    aria-required="true"
                    aria-describedby="gdpr-consent-error"
                    class="mt-1 h-5 w-5 text-portfolio-accent border-portfolio-accent/30 rounded-sm focus:ring-2 focus:ring-portfolio-accent focus:ring-offset-0 transition-colors duration-200 @error('gdpr_consent') border-portfolio-error ring-1 ring-portfolio-error/20 @enderror"
                >
                <label for="gdpr_consent" class="ml-3 text-sm text-portfolio-text/80">
                    {{ __('portfolio.contact.form.gdpr_consent') }}
                </label>
            </div>
            @error('gdpr_consent')
                <p id="gdpr-consent-error" class="mt-2 text-sm text-portfolio-error flex items-start gap-1">
                    <span aria-hidden="true">⚠</span>
                    <span>{{ $message }}</span>
                </p>
            @enderror

            <button
                type="submit"
                wire:loading.attr="disabled"
                class="w-full bg-portfolio-accent hover:bg-portfolio-accent-dark disabled:bg-portfolio-accent/50 disabled:cursor-not-allowed text-white font-semibold py-3 px-6 rounded-sm transition-all duration-200 hover:shadow-md"
            >
                <span wire:loading.remove>{{ __('portfolio.contact.form.send_button') }}</span>
                <span wire:loading>{{ __('portfolio.contact.form.sending') }}</span>
            </button>
        </form>
    @endif
</div>