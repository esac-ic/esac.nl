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
    ],
    callbacks: {
        onImageUpload: function (files) {
            uploadFile(files[0], this);
        },
        onMediaDelete: function ($target) {
            deleteFile($target, this);
        }
    }
};

function uploadFile (file, summernote) {
    data = new FormData();
    data.append('image', file);

    axios
        .post($(summernote).data('images-url'), data)
        .then(response => {
            $(summernote).summernote('insertImage', response.data);
        });
}

function deleteFile ($target, summernote) {
    if ($target.is('img')) {
        let path = $target.attr('src');

        if (path.startsWith('data:image')) {
            // This is a base64 image, so no need to remove it from storage.
            return;
        }

        axios
            .delete($(summernote).data('images-url'), {params: {
                    path: path,
                }});
    }
}
