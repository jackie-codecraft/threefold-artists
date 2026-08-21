import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

document.addEventListener('submit', (event) => {
    const form = event.target;

    if (!(form instanceof HTMLFormElement) || form.dataset.submitting === 'true' || !form.checkValidity()) {
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
