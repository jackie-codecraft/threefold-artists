@if(filled(config('services.turnstile.site_key')))
    <div class="cf-turnstile" data-sitekey="{{ config('services.turnstile.site_key') }}"></div>

    @once
        @push('scripts')
            <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
        @endpush
    @endonce
@endif
{{-- Error pages render without the shared $errors bag --}}
@isset($errors)
    @error('cf-turnstile-response')
        <p class="text-red-600 text-sm mt-3 dark:text-red-400" role="alert">{{ $message }}</p>
    @enderror
@endisset
