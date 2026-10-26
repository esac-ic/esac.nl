import $ from 'jquery';
import { request } from './http';
import 'summernote/dist/summernote-bs4';
import 'summernote/dist/summernote-bs4.css';

// Rich text fields are textareas with data-images-url, where the editor uploads its images.
const $editors = $('textarea[data-images-url]');

// The code view doesn't save its changes, so leaving it closes it first, then repeats the click that left it.
let $lastClick = null;
$(document).on('mousedown', event => $lastClick = $(event.target));
$(document).on('mouseup', () => $lastClick = null);

function closeCodeView(event) {
    const $editor = $(event.target);

    if ($editor.summernote('codeview.isActivated')) {
        $editor.summernote('codeview.deactivate');

        if ($lastClick !== null && !$lastClick.hasClass('btn-codeview')) {
            $lastClick.trigger('click');
        }
    }
}

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
}).on('summernote.blur.codeview', closeCodeView);
