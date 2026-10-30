{{-- Light/dark switch. Icons swap via the dark: variant so they're right before Alpine boots. --}}
<button type="button" @click="$store.theme.toggle()" :aria-pressed="$store.theme.dark.toString()" aria-label="Dark mode" title="Toggle dark mode"
        {{ $attributes->class('flex h-9 w-9 shrink-0 items-center justify-center rounded-full border border-line text-ink-muted hover:border-stage-gold hover:text-ink dark:text-stage-gold dark:hover:text-stage-gold-light') }}>
    <svg class="h-[18px] w-[18px] dark:hidden" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
    </svg>
    <svg class="hidden h-[18px] w-[18px] dark:block" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" aria-hidden="true">
        <circle cx="12" cy="12" r="4"/>
        <path stroke-linecap="round" d="M12 2v2m0 16v2M4.93 4.93l1.41 1.41m11.32 11.32 1.41 1.41M2 12h2m16 0h2M4.93 19.07l1.41-1.41m11.32-11.32 1.41-1.41"/>
    </svg>
</button>
