var items = [];
var count = 0;
try {
    var currentPhotoAlbum = photoAlbum.id;
    for (var photo in photos.data) {
        items.push({ src: photos.data[photo].link, w: photos.data[photo].width, h: photos.data[photo].height });
    }
    $("p#albumDesciption").text($("p#albumDesciption").text().replace(/\n/g, "<br>"));
}
catch(err) {
    $("p#albumDesciption").text($("p#albumDesciption").text().replace(/\n/g, "<br>"));
}

function openGallery(index) {
    var pswpElement = document.querySelectorAll('.pswp')[0];
    var options = {
        index: parseInt(index)
    };
    var gallery = new PhotoSwipe(pswpElement, PhotoSwipeUI_Default, items, options);
    gallery.init();
}


var files;
var albumName;
var albumDescription = "";
var captureDate = null;

function uploadPhotos() {
    var fileInput = document.getElementById('file-select');
    var albumNameInput = document.getElementById("inputTitle");
    var albumDescriptionInput = document.getElementById("textareaDescription");
    var captureDateInput = document.getElementById("CaptureDate");

    if(albumNameInput  && albumDescriptionInput && captureDateInput){
        if(albumNameInput.checkValidity() && albumDescriptionInput.checkValidity() && captureDateInput.checkValidity()){
            albumName = albumNameInput.value;
            albumDescription = albumDescriptionInput.value;
            captureDate = captureDateInput.value;
            var justPhoto = (albumName === undefined || albumDescription === undefined);
    
            if(!justPhoto && (albumName.length >= 250 || albumDescription.length >= 250)) {
                alert("The album name and description have to consist of less than 250 characters.");
                return;
            }
        } else return;
    }

    if(fileInput.checkValidity()){
        files = Array.from(fileInput.files);
        numberOfPhotos = files.length;
        updateProgressBar();
        processPhotos();
    } else alert("Selecteer een aantal foto's om toe te voegen."); 
}

var fileIndex = 0;
function processPhotos(){
    ChangebuttonState("Uploading photos...");
    resizeThumbnail(files[fileIndex]).then(function({thumbnail, file}){
        downscalePhoto(file).then(function({photo, file}){
            if (currentPhotoAlbum != undefined) { // upload photo without album
                addPhotoToAlbum(thumbnail, photo, fileIndex).then(function(index){
                    if(index >= (files.length -1)){
                        location.reload();
                    } else{
                        updateProgressBar();
                        fileIndex++;
                        processPhotos();
                    }     
                });
            } else{
                addAlbum(thumbnail, photo, albumName, albumDescription, captureDate, fileIndex).then(function(index){
                    if(index >= (files.length -1)){
                        location.reload();
                    } else{
                        updateProgressBar();
                    }
                });
                fileIndex++;
                processPhotos();
            }     
        }) 
    });
    
}

function downscalePhoto(file) {
    return new Promise(function (resolve, reject) {
        loadImage(file, 
            function(canvas){ 
                canvas.toBlob(function (blob) {
                        resolve({photo: blob, file: file});
                    }, 'image/jpeg', 0.2    
                ); 
            },    
            {
                canvas: true,
                orientation: true,
                downsamplingRatio: 0.2
            }
        );
    });
}

function resizeThumbnail(file) {
    return new Promise(function (resolve, reject) {
        loadImage(file, 
            function(canvas){ 
                canvas.toBlob(function (blob) {
                        resolve({thumbnail: blob, file: file});
                    }, 'image/jpeg', 0.9    
                ); 
            },    
            {
            maxWidth: 354,
            maxHeight: 354,
            minWidth: 354,
            minHeight: 354,
            crop: true,
            canvas: true,
            orientation: true
            }
        );
    });   
}

function addAlbum(thumbnail, photo, albumName, albumDescription, captureDate, fileIndex) {
    return new Promise(function (resolve, reject) {     
        var type = "POST";
        var formData = new FormData();    
        formData.append("title", albumName);
        formData.append("description", albumDescription);
        formData.append("captureDate", captureDate);
        formData.append('thumbnails[]',thumbnail);
        formData.append('photos[]', photo);
        formData.append("_token", window.Laravel.csrfToken);

        $.ajax({
            data: formData,
            type: type,
            contentType: false,
            processData: false,
            success: function (result) {
                currentPhotoAlbum = result;
                resolve(fileIndex);
            },
            error: function (request, error) {
                console.log(arguments);
                alert(" Can't do because: " + error);
            }
        });
    });
}

function addPhotoToAlbum(thumbnail, photo, fileIndex){  
    return new Promise(function (resolve, reject) {     
        var formData = new FormData();
        formData.append('thumbnails[]',thumbnail);
        formData.append('photos[]', photo);
            
        var url = currentPhotoAlbum;
        var type = "POST";
        formData.append("_token", window.Laravel.csrfToken);
    
        $.ajax({
            url: window.location.origin + "/photoalbums" + "/" + url,
            data: formData,
            type: type,
            contentType: false,
            processData: false,
            success: function (result) {
                resolve(fileIndex);
            },
            error: function (request, error) {
                console.log(arguments);
                alert(" Can't do because: " + error);
            }
        });
    }); 
}

function ChangebuttonState(text){
    var button = $('button#submit');
    var loadingText = '<i class="icon ion-refreshing"></i> ' + text;
    if (button.html() !== loadingText) {
        button.data('original-text', button.html());
        button.html(loadingText);
        button.attr("disabled", true);
    }
}

var numberOfPhotosProgressed = -1;
var numberOfPhotos;
//function called when 
function updateProgressBar(){
    //get the progressbar
    var progressbar = document.getElementById("progressBar");
    var progressText = document.getElementById("progressText");
    if(numberOfPhotosProgressed == -1){
        //show progressbar if number of PhotosProgressed is zero
        var progress = document.getElementById("progress");
        progress.style.visibility = "visible"; 
        numberOfPhotosProgressed++;
    } else{
        numberOfPhotosProgressed++;
        var interval = 100 / numberOfPhotos;
        progressbar.style.width = (parseFloat(progressbar.style.width.replace('%', '')) + interval) + '%';
        progressText.innerHTML = numberOfPhotosProgressed  + " Photos uploaded";
    }
}

