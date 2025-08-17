<div>
    @if (session()->has('success'))
        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if ($submitted)
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            <p class="font-semibold">Thank you for your message!</p>
            <p>We will get back to you soon.</p>
        </div>
    @else
        <form wire:submit="submit" class="space-y-6">
            <div>
                <label for="name" class="block text-sm font-medium text-slate-700 mb-2">Name *</label>
                <input 
                    type="text" 
                    wire:model="name" 
                    id="name" 
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                >
                @error('name') 
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p> 
                @enderror
            </div>
            
            <div>
                <label for="email" class="block text-sm font-medium text-slate-700 mb-2">Email *</label>
                <input 
                    type="email" 
                    wire:model="email" 
                    id="email" 
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror"
                >
                @error('email') 
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p> 
                @enderror
            </div>
            
            <div>
                <label for="subject" class="block text-sm font-medium text-slate-700 mb-2">Subject</label>
                <input 
                    type="text" 
                    wire:model="subject" 
                    id="subject" 
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('subject') border-red-500 @enderror"
                >
                @error('subject') 
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p> 
                @enderror
            </div>
            
            <div>
                <label for="message" class="block text-sm font-medium text-slate-700 mb-2">Message *</label>
                <textarea 
                    wire:model="message" 
                    id="message" 
                    rows="5" 
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('message') border-red-500 @enderror"
                ></textarea>
                @error('message') 
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p> 
                @enderror
            </div>
            
            <div class="flex items-start">
                <input 
                    type="checkbox" 
                    wire:model="gdpr_consent" 
                    id="gdpr_consent" 
                    class="mt-1 h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 @error('gdpr_consent') border-red-500 @enderror"
                >
                <label for="gdpr_consent" class="ml-3 text-sm text-slate-600">
                    I consent to the processing of my personal data in accordance with the privacy policy. *
                </label>
            </div>
            @error('gdpr_consent') 
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p> 
            @enderror
            
            <button 
                type="submit" 
                wire:loading.attr="disabled"
                class="w-full bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 text-white font-semibold py-3 px-6 rounded-lg transition-colors"
            >
                <span wire:loading.remove>Send Message</span>
                <span wire:loading>Sending...</span>
            </button>
        </form>
    @endif
</div>