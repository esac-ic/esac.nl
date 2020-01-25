function validateFileSize() {
    var input, file;

    if (!window.FileReader) {
        return true;
    }

    input = document.getElementById('thumbnail');
    if(undefined )


    if (!input) {
        return true;
    }
    if (!input.files) {
        return true;
    }
    else if (!input.files[0]) {
        return true;
    }
    else {
        file = input.files[0];
        return file.size < 20971520; //file needs to be smaller dan 20 mb
    }
};