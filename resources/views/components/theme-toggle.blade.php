{{-- Theme switcher: each click cycles System → Light → Dark. The icon shown follows
     data-theme-mode on <html> (see .theme-icon in app.css), so it's right before Alpine boots. --}}
<button type="button" @click="$store.theme.cycle()" aria-label="Theme" title="Theme" :aria-label="$store.theme.label" :title="$store.theme.title"
        {{ $attributes->class('flex h-9 w-9 shrink-0 items-center justify-center rounded-full border border-line text-ink-muted hover:border-stage-gold hover:text-ink dark:text-stage-gold dark:hover:text-stage-gold-light') }}>
    <svg class="theme-icon theme-icon-system h-[18px] w-[18px]" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" aria-hidden="true">
        <rect x="3" y="4" width="18" height="12" rx="2"/>
        <path stroke-linecap="round" d="M8 20h8M12 16v4"/>
    </svg>
    <svg class="theme-icon theme-icon-light h-[18px] w-[18px]" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" aria-hidden="true">
        <circle cx="12" cy="12" r="4"/>
        <path stroke-linecap="round" d="M12 2v2m0 16v2M4.93 4.93l1.41 1.41m11.32 11.32 1.41 1.41M2 12h2m16 0h2M4.93 19.07l1.41-1.41m11.32-11.32 1.41-1.41"/>
    </svg>
    <svg class="theme-icon theme-icon-dark h-[18px] w-[18px]" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
    </svg>
</button>
