import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Theme mode: system (follow the OS), light or dark. The initial mode is set by an
// inline script in the layout head; only an explicit light/dark choice is saved.
const systemDark = window.matchMedia('(prefers-color-scheme: dark)');
const THEME_MODES = ['system', 'light', 'dark'];
const THEME_NAMES = { system: 'System', light: 'Light', dark: 'Dark' };

Alpine.store('theme', {
    mode: document.documentElement.dataset.themeMode || 'system',
    dark: document.documentElement.dataset.theme === 'dark',

    get next() {
        return THEME_MODES[(THEME_MODES.indexOf(this.mode) + 1) % THEME_MODES.length];
    },

    get title() {
        return `Theme: ${THEME_NAMES[this.mode]}`;
    },

    get label() {
        return `${this.title}. Switch to ${THEME_NAMES[this.next].toLowerCase()}.`;
    },

    set(mode) {
        this.mode = mode;
        this.dark = mode === 'system' ? systemDark.matches : mode === 'dark';
        document.documentElement.dataset.theme = this.dark ? 'dark' : 'light';
        document.documentElement.dataset.themeMode = mode;

        try {
            if (mode === 'system') {
                localStorage.removeItem('theme');
            } else {
                localStorage.setItem('theme', mode);
            }
        } catch (e) {}
    },

    cycle() {
        this.set(this.next);
    },
});

systemDark.addEventListener('change', () => {
    const theme = Alpine.store('theme');

    if (theme.mode === 'system') {
        theme.set('system');
    }
});

Alpine.start();

document.addEventListener('submit', (event) => {
    const form = event.target;

    if (event.defaultPrevented || !(form instanceof HTMLFormElement) || form.dataset.submitting === 'true' || !form.checkValidity()) {
        return;
    }

    form.dataset.submitting = 'true';
    form.setAttribute('aria-busy', 'true');

    form.querySelectorAll('button[type="submit"], input[type="submit"]').forEach((control) => {
        control.disabled = true;
        control.setAttribute('aria-disabled', 'true');

        if (control.dataset.loadingLabel && control instanceof HTMLButtonElement) {
            control.dataset.originalLabel = control.textContent;
            control.textContent = control.dataset.loadingLabel;
        }
    });
});
