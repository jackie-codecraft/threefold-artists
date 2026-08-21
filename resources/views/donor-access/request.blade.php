<x-layouts.app title="Manage My Donations" metaDescription="Securely access your Threefold Artists donation history.">
    <section class="py-16 sm:py-20">
        <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="text-xs font-semibold tracking-[0.2em] uppercase text-gray-400 mb-4">Supporter access</p>
            <h1 class="font-display text-4xl sm:text-5xl font-light text-theatre-black">Manage My Donations</h1>
            <p class="text-gray-600 mt-5">Enter the email address used for your donation. If it matches an eligible record, we’ll send a secure one-time link.</p>

            @if(session('error'))
                <div class="mt-6 border-l-4 border-curtain-red bg-red-50 p-4 text-sm text-curtain-red">{{ session('error') }}</div>
            @endif

            <form action="{{ route('donor-access.send') }}" method="POST" class="mt-8 space-y-6">
                @csrf
                <div>
                    <label for="email" class="block text-xs font-semibold tracking-[0.15em] uppercase text-gray-500 mb-2">Email address</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autocomplete="email" class="w-full border-0 border-b border-gray-300 bg-transparent px-0 py-3 text-theatre-black focus:border-theatre-black focus:ring-0">
                    @error('email')<p class="text-sm text-curtain-red mt-2">{{ $message }}</p>@enderror
                </div>
                <button type="submit" class="w-full px-8 py-3.5 bg-theatre-black text-white text-sm font-semibold tracking-wide uppercase hover:bg-gray-800 transition-colors">Email me a secure link</button>
            </form>
        </div>
    </section>
</x-layouts.app>
