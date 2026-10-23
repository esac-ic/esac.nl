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
        ['myotherbutton', ['emailsDropDown']],
    ],
    callbacks: {
        onImageUpload: function (files) {
            uploadFile(files[0], this);
        },
    }
};

function uploadFile (file, summernote) {
    const data = new FormData();
    data.append('image', file);

    axios
        .post('/admin/editor-images', data)
        .then(response => {
            $(summernote).summernote('insertImage', response.data);
        });
}
