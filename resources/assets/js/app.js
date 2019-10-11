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
        .post('/images/upload', data)
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
            .delete('/images/delete', {params: {
                    path: path,
                }});
    }
}

// Cookie utils

// creates a cookie
window.createCookie = function (name, value, days) {
    var expires;
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toGMTString();
    }
    else {
        expires = "";
    }
    document.cookie = name + "=" + value + expires + "; path=/";
};

//retrives a cookie
window.getCookie = function (c_name) {
    if (document.cookie.length > 0) {
        c_start = document.cookie.indexOf(c_name + "=");
        if (c_start != -1) {
            c_start = c_start + c_name.length + 1;
            c_end = document.cookie.indexOf(";", c_start);
            if (c_end == -1) {
                c_end = document.cookie.length;
            }
            return unescape(document.cookie.substring(c_start, c_end));
        }
    }
    return "";
};