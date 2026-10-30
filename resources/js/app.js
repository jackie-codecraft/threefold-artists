import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Light/dark theme. The initial value is set by an inline script in the layout head;
// with no saved choice we keep following the visitor's OS setting.
const systemDark = window.matchMedia('(prefers-color-scheme: dark)');

const storedTheme = () => {
    try {
        return localStorage.getItem('theme');
    } catch (e) {
        return null;
    }
};

Alpine.store('theme', {
    dark: document.documentElement.dataset.theme === 'dark',

    apply(dark) {
        this.dark = dark;
        document.documentElement.dataset.theme = dark ? 'dark' : 'light';
    },

    toggle() {
        this.apply(!this.dark);

        try {
            localStorage.setItem('theme', this.dark ? 'dark' : 'light');
        } catch (e) {}
    },
});

systemDark.addEventListener('change', (event) => {
    if (!storedTheme()) {
        Alpine.store('theme').apply(event.matches);
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
