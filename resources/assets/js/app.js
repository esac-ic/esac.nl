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

