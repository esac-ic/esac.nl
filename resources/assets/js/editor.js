import $ from 'jquery';
import { request } from './http';
import 'summernote/dist/summernote-bs4';
import 'summernote/dist/summernote-bs4.css';

// Rich text fields are textareas with data-images-url, where the editor uploads its images.
const $editors = $('textarea[data-images-url]');

function uploadImage(file, editor) {
    const data = new FormData();
    data.append('image', file);

    request('POST', $(editor).data('images-url'), data)
        .then(url => $(editor).summernote('insertImage', url));
}

$editors.summernote({
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
        onImageUpload(files) {
            uploadImage(files[0], this);
        },
    },
// Summernote only copies its content to the textarea on changes outside the code view
}).on('summernote.change.codeview', (event, html) => $(event.target).val(html));
