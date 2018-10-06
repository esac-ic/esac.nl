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


function uploadPhoto() {
    var files = document.getElementById('file-select').files;
    var formData = new FormData();
    var thumbnailsPromises = [];

    for (var i = 0; i < files.length; i++) {
        var file = files[i];
        formData.append("photos[]", file, file.name);
        thumbnailsPromises.push(resizeImage(file, 100,100));
    }

    Promise.all(thumbnailsPromises).then(function (results){
        for(var result in results){
            var thumbnail = results[result];
            formData.append("thumbnails[]", thumbnail);
        }

        var url =  photoAlbum.id;
        var type = "POST";
        formData.append("_token",window.Laravel.csrfToken);

        $.ajax({
            url: url,
            data: formData,
            type: type,
            contentType: false,
            processData: false, 
            success: function (result) {
                location.reload();
            },
            error : function(){
            }
        });   
    });        
}

function resizeImage(photo, width, height) {
    return new Promise(function (resolve, reject) {
        var reader = new FileReader();
        reader.onload = function (e) { img.src = e.target.result }
        reader.readAsDataURL(photo);
    
        var img = new Image();
        img.onload=function(){
            var canvas=document.createElement("canvas");
            var context=canvas.getContext("2d");
            canvas.width= 100;
            canvas.height= 100;
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
            }, 'image/png');
        }
    });
}