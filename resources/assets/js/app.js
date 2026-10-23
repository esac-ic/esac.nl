require('./bootstrap');
require('./vue');

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
        onMediaDelete: function ($target, editor) {
            deleteFile($target)
        }
    }
};

function uploadFile (file, summernote) {
    data = new FormData();
    data.append('image', file);

    axios
        .post('/admin/editor-images', data)
        .then(response => {
            $(summernote).summernote('insertImage', response.data);
        });
}

function deleteFile ($target) {
    if ($target.is('img')) {
        let path = $target.attr('src');

        if (path.startsWith('data:image')) {
            // This is a base64 image, so no need to remove it from storage.
            return;
        }

        axios
            .delete('/admin/editor-images', {params: {
                    path: path,
                }});
    }
}
