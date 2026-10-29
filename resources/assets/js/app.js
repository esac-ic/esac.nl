import './vendor/jquery-bootstrap';
import { confirmDialog } from './confirm-dialog';

// Heavier parts load only on the pages that use them
if (document.querySelector('[data-vue]')) {
    import('./vue');
}
if (document.querySelector('.js-datatable')) {
    import('./vendor/datatables');
}
if (document.querySelector('textarea[data-images-url]')) {
    import('./editor');
}

// On wide screens the menu with data-transparent-at-top shows the header through it until the page scrolls.
// Bootstrap puts its breakpoints in CSS variables; the menu expands at lg.
const wide = window.matchMedia(`(min-width: ${getComputedStyle(document.documentElement).getPropertyValue('--breakpoint-lg')})`);
document.querySelectorAll('[data-transparent-at-top]').forEach(nav => {
    const update = () => nav.classList.toggle('bg-dark', !wide.matches || window.scrollY > 15);

    update();
    window.addEventListener('scroll', update, { passive: true });
    window.addEventListener('resize', update);
});

// Warn before leaving a form with unsaved changes.
document.querySelectorAll('form[data-warn-unsaved]').forEach(form => {
    let changed = false;
    form.addEventListener('input', () => changed = true);
    form.addEventListener('change', () => changed = true);
    form.addEventListener('submit', () => changed = false);
    window.addEventListener('beforeunload', event => {
        if (changed) {
            event.preventDefault();
        }
    });
});

// Forms with data-confirm ask first, e.g. <form data-confirm="Remove the book?" data-confirm-button="Remove">.
document.addEventListener('submit', event => {
    const form = event.target;
    if (!form.dataset.confirm || form.dataset.confirmed) {
        return;
    }

    event.preventDefault();
    confirmDialog({
        title: form.dataset.confirmTitle,
        message: form.dataset.confirm,
        button: form.dataset.confirmButton,
        danger: form.dataset.confirmDanger !== 'false',
    }).then(confirmed => {
        if (confirmed) {
            // requestSubmit fires the submit event straight away, so the flag only lets this one through.
            form.dataset.confirmed = 'true';
            form.requestSubmit(event.submitter);
            delete form.dataset.confirmed;
        }
    });
});
