import './bootstrap';
import './vue';

window.summernoteSettings = {
    height: 300,
    toolbar: [
        ['style', ['style']],
        ['font', ['bold', 'italic', 'underline', 'clear']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['height', ['height']],
        ['table', ['table']],
        ['insert', ['link', 'picture', 'hr']],
        ['view', ['fullscreen', 'codeview']],
        ['help', ['help']],
    ],
    callbacks: {
        onImageUpload: function (files) {
            uploadFile(files[0], this);
        }
    }
};

function uploadFile (file, summernote) {
    const data = new FormData();
    data.append('image', file);

    axios
        .post($(summernote).data('images-url'), data)
        .then(response => {
            $(summernote).summernote('insertImage', response.data);
        });
}

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

/**
 * Asks for confirmation in the page's own dialog, resolving to whether the action was confirmed.
 * Cancel has the focus, so pressing Enter by accident does nothing.
 */
window.confirmDialog = ({ title = 'Are you sure?', message = '', button = 'Confirm', danger = true }) => new Promise(resolve => {
    const $dialog = window.jQuery('#confirm-dialog');
    let confirmed = false;

    $dialog.find('#confirm-dialog-title').text(title);
    $dialog.find('#confirm-dialog-message').text(message);
    $dialog.find('#confirm-dialog-confirm')
        .text(button)
        .toggleClass('btn-danger', danger)
        .toggleClass('btn-primary', !danger)
        .off('click')
        .one('click', () => {
            confirmed = true;
            $dialog.modal('hide');
        });
    $dialog.off('shown.bs.modal').one('shown.bs.modal', () => $dialog.find('.modal-footer [data-dismiss=modal]').trigger('focus'));
    $dialog.off('hidden.bs.modal').one('hidden.bs.modal', () => resolve(confirmed));
    $dialog.modal('show');
});

// Forms with data-confirm ask first, e.g. <form data-confirm="Remove the book?" data-confirm-button="Remove">.
document.addEventListener('submit', event => {
    const form = event.target;
    if (!form.dataset.confirm || form.dataset.confirmed) {
        return;
    }

    event.preventDefault();
    window.confirmDialog({
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
