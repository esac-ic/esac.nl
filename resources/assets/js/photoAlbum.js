var PhotoSwipeItems = []; // Contains the photos used by photoswipe.
try {
    var currentPhotoAlbum = photoAlbum.id;
    for (var photo in photos) {
        PhotoSwipeItems.push({ src: photos[photo].link, w: photos[photo].width, h: photos[photo].height });
    }
}
catch(err) {
    console.log(err);
}
finally{
    $("p#albumDesciption").text($("p#albumDesciption").text().replace(/\n/g, "<br>"));
}

//function to open photswipe
//index: selected photo inde by user
function openGallery(index) {
    var pswpElement = document.querySelectorAll('.pswp')[0];
    var options = {
        index: parseInt(index)
    };
    var gallery = new PhotoSwipe(pswpElement, PhotoSwipeUI_Default, PhotoSwipeItems, options);
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

    //check if albumname, description and date exists.
    if(albumNameInput  && albumDescriptionInput && captureDateInput){
        albumName = albumNameInput.value;
        albumDescription = albumDescriptionInput.value;
        captureDate = captureDateInput.value;
        //check if albumname, description is not empty and not exceed 250 characters.
        if(albumName.length >= 250 || albumDescription.length >= 250) {
            alert("The album name and description have to consist of less than 250 characters.");
            return;
        }
    }
    //check if selectedfile input is valid (not null)
    if(fileInput.checkValidity()){
        files = Array.from(fileInput.files);
        numberOfPhotos = files.length;
        updateProgressBar(); //show progressbar
        processPhotos(0); //start processing photos
    }
}


//recusrive function the process photos.
function processPhotos(fileIndex){
    ChangebuttonState("Uploading photos...");
    resizeImage(files[fileIndex], 356, 356, 0.5).then(function(thumbnail){
        downscaleImage(files[fileIndex], 0.5).then(function(photo){
            if(fileIndex != 0){
                processPhotos((fileIndex + 1));
            }
            if (currentPhotoAlbum != undefined) { //if currentAlbum is undefined then it means that we are adding an album.
                addPhotoToAlbum(thumbnail, photo, fileIndex).then(function(index){
                    if(index == 0){
                        processPhotos((fileIndex + 1));
                    }
                    if(index >= (files.length -1)){ //if returned index equals files length then last photo has uploaded. refresh page
                        location.reload();
                    } else{
                        updateProgressBar();
                    }
                });
            } else{
                createAlbum(thumbnail, photo, albumName, albumDescription, captureDate, fileIndex).then(function(index){
                    if(index == 0){
                        processPhotos((fileIndex + 1));
                    }
                    if(index >= (files.length -1)){ //if returned index equals files length then last photo has uploaded. refresh page
                        location.reload();
                    } else{
                        updateProgressBar();
                    }
                });
            }
        });
    });
}

//Api Post request to backend to add one album.
//thumbnail: thumbnail of photo that will be uploaded
//photo: photo that will be uploaded
//albumname: name of album that will be created
//albumdescription: description of album that will be created
//captureDate: Capture date of the photos
//fileIndex: fileIndex of current photo (This is given to ensure to reload after last photo)
function createAlbum(thumbnail, photo, albumName, albumDescription, captureDate, fileIndex) {
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

//Api Post request to backend to add a photo to an album.
//thumbnail: thumbnail of photo that will be uploaded
//photo: photo that will be uploaded
//fileIndex: fileIndex of current photo (This is given to ensure to reload after last photo)
function addPhotoToAlbum(thumbnail, photo, fileIndex){
    return new Promise(function (resolve, reject) {
        var formData = new FormData();
        formData.append('fileName', files[fileIndex].name)
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

//Function to change state and text of the submit button.
function ChangebuttonState(text){
    var button = $('button#submit');
    var loadingText = '<i class="icon ion-refreshing"></i> ' + text;
    //if button is not loading disable it and set loading text.
    if (button.html() !== loadingText) {
        button.data('original-text', button.html());
        button.html(loadingText);
        button.attr("disabled", true);
    }
}

var numberOfPhotosProgressed = -1;
var numberOfPhotos;
//Function to update the progress after photo is done uploading
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
        //Increase the progessbar and change the label
        numberOfPhotosProgressed++;
        var interval = 100 / numberOfPhotos;
        progressbar.style.width = (parseFloat(progressbar.style.width.replace('%', '')) + interval) + '%';
        progressText.innerHTML = numberOfPhotosProgressed  + " Photos uploaded";
    }
}