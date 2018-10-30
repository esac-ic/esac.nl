var items = [];

for (var photo in photos) {
    items.push({ src: photos[photo][0], w: photos[photo][1], h: photos[photo][2] });
}

function openGallery(index) {
    var pswpElement = document.querySelectorAll('.pswp')[0];
    var options = {
        index: parseInt(index)
    };
    var gallery = new PhotoSwipe(pswpElement, PhotoSwipeUI_Default, items, options);
    gallery.init();
}

function addAlbum(formData) {
    var type = "POST";
    formData.append("_token", window.Laravel.csrfToken);

    $.ajax({
        url: '/newAlbum',
        data: formData,
        type: type,
        contentType: false,
        processData: false,
        success: function (result) {
            location.reload();
        },
        error: function (request, error) {
            console.log(arguments);
            alert(" Can't do because: " + error);
        }
    });
}

function addPhotoToAlbum(formData){
    var url = photoAlbum.id;
    var type = "POST";
    formData.append("_token", window.Laravel.csrfToken);

    $.ajax({
        url: url,
        data: formData,
        type: type,
        contentType: false,
        processData: false,
        success: function (result) {
            location.reload();
        },
        error: function (request, error) {
            console.log(arguments);
            alert(" Can't do because: " + error);
        }
    });
}

function uploadPhoto() {
    var photos = document.getElementById('file-select').files;
    var albumName = $("input#inputTitle").val();
    var albumDescription = $("input#inputDescription").val();
    var thumbnails = resizeImages(photos);
    
    var formData = new FormData();
    formData.append("title", albumName);
    formData.append("description", albumDescription);
    for(var i; i < photos.length; i++){
        formData.append("photos[]", photos[i]);
    }
      
    Promise.all(thumbnails).then(function(results){
        for (var thumbnail in thumbnails) {
            formData.append("thumbnails[]", thumbnails[thumbnail]);
        }
        if (!albumName && !albumDescription) { // upload photo without album
            addPhotoToAlbum(formData);
        } else{
            addAlbum(formData);
        }
    });
}

function resizeImages(photos) {
    var promises = [];
    for (var i = 0; i < photos.length; i++) {
        promises.push(new Promise(function (resolve, reject) {
            var reader = new FileReader();
            reader.onload = function (e) { img.src = e.target.result }
            reader.readAsDataURL(photos[i]);
    
            var img = new Image();
            img.onload = function () {
                var canvas = document.createElement("canvas");
                var context = canvas.getContext("2d");
                var imageratio = img.height / img.width;
                canvas.width = 1440;
                canvas.height = canvas.width * imageratio;
                context.drawImage(img,
                    0,
                    0,
                    img.width,
                    img.height,
                    0,
                    0,
                    canvas.width,
                    canvas.height
                );
                canvas.toBlob(function (blob) {
                    resolve(blob);
                }, 'image/jpeg', 0.8);
            }
        }));
    }
    return promises;
}
