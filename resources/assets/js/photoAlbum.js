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

//button spinner
function ChangebuttonState(text){
    var button = $('button#submit');
    var loadingText = '<i class="icon ion-refreshing"></i> ' + text;
    if (button.html() !== loadingText) {
        button.data('original-text', button.html());
        button.html(loadingText);
        button.attr("disabled", true);
    }
}

function addAlbum(thumbnailResults, photoResults, albumName, albumDescription, captureDate) {
    var formData = new FormData();    
    formData.append("title", albumName);
    formData.append("description", albumDescription);
    formData.append("captureDate", captureDate);
    if(thumbnailResults.length< 5){
        index= thumbnailResults.length
    } else index = 5;
    for(var i=0; i < index; i++){
        formData.append('thumbnails[]',thumbnailResults[i]);
        formData.append('photos[]', photoResults[i]);
    }
    
    var type = "POST";
    formData.append("_token", window.Laravel.csrfToken);

    $.ajax({
        data: formData,
        type: type,
        contentType: false,
        processData: false,
        success: function (result) {
            currentPhotoAlbum = "photoalbums/" + result;
            if(thumbnailResults.length> 5){
                addPhotoToAlbum(thumbnailResults, photoResults, index);
            } else{
                location.reload();
            }
        },
        error: function (request, error) {
            console.log(arguments);
            alert(" Can't do because: " + error);
        }
    });
}

function addPhotoToAlbum(thumbnailResults, photoResults, index){
    var i = index;
    var j = 0;
    if((thumbnailResults.length - index) <= 5){
        j = (thumbnailResults.length - index);
    } else{
        j = 5;
    }
    var formData = new FormData();    
    for(i; i < index+j; i++){
        formData.append('thumbnails[]',thumbnailResults[i]);
        formData.append('photos[]', photoResults[i]);
    }
    var url = currentPhotoAlbum;
    var type = "POST";
    formData.append("_token", window.Laravel.csrfToken);

        $.ajax({
            url: url,
            data: formData,
            type: type,
            contentType: false,
            processData: false,
            success: function (result) {
                count+=5;
                if(count >= thumbnailResults.length){
                    location.reload();
                } else{
                    addPhotoToAlbum(thumbnailResults, photoResults, i);
                }
            },
            error: function (request, error) {
                console.log(arguments);
                alert(" Can't do because: " + error);
            }
    });
    
}

function uploadPhoto() {
    var files = document.getElementById('file-select').files;
    var albumName = $("input#inputTitle").val();
    var albumDescription = $("textarea#textareaDescription").val();
    var captureDate = $("input#CaptureDate").val();

    if(files.length != 0 && albumName != '' && albumDescription  != ''){
        if(albumName.length < 250 && albumDescription.length < 250){
            ChangebuttonState("Processing photos...")
            var thumbnails = processThumbnails(files);
            var photos = processPhotos(files);
            
            Promise.all(thumbnails).then(function(thumbnailResults){
                Promise.all(photos).then(function(photosResults){
                    ChangebuttonState('Sending photos...')
                    if (albumName == undefined && albumDescription  == undefined) { // upload photo without album
                        addPhotoToAlbum(thumbnailResults, photosResults, 0);
                    } else{
                        addAlbum(thumbnailResults, photosResults, albumName, albumDescription, captureDate); 
                    }
                });
            });
        }
    }
}

function processPhotos(photos) {
    var promises = [];
    for (var i = 0; i < photos.length; i++) {
        promises.push(new Promise(function (resolve, reject) {
            loadImage(photos[i], 
                function(canvas){ 
                    canvas = downScaleCanvas(canvas, 0.5);
                    canvas.toBlob(function (blob) {
                            resolve(blob);
                        }, 'image/jpeg', 0.9    
                    ); 
                },    
                {
                    canvas: true,
                    orientation: true
                }
            );
        }));
    }
    return promises;
}

function processThumbnails(photos) {
    var promises = [];
    for (var i = 0; i < photos.length; i++) {
        promises.push(new Promise(function (resolve, reject) {
            loadImage(photos[i], 
                function(canvas){ 
                    canvas.toBlob(function (blob) {
                            resolve(blob);
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
        }));
    }
    return promises;
}
function downScaleCanvas(cv, scale) {
    if (!(scale < 1) || !(scale > 0)) throw ('scale must be a positive number <1 ');
    var sqScale = scale * scale; // square scale = area of source pixel within target
    var sw = cv.width; // source image width
    var sh = cv.height; // source image height
    var tw = Math.floor(sw * scale); // target image width
    var th = Math.floor(sh * scale); // target image height
    var sx = 0, sy = 0, sIndex = 0; // source x,y, index within source array
    var tx = 0, ty = 0, yIndex = 0, tIndex = 0; // target x,y, x,y index within target array
    var tX = 0, tY = 0; // rounded tx, ty
    var w = 0, nw = 0, wx = 0, nwx = 0, wy = 0, nwy = 0; // weight / next weight x / y
    // weight is weight of current source point within target.
    // next weight is weight of current source point within next target's point.
    var crossX = false; // does scaled px cross its current px right border ?
    var crossY = false; // does scaled px cross its current px bottom border ?
    var sBuffer = cv.getContext('2d').
    getImageData(0, 0, sw, sh).data; // source buffer 8 bit rgba
    var tBuffer = new Float32Array(3 * tw * th); // target buffer Float32 rgb
    var sR = 0, sG = 0,  sB = 0; // source's current point r,g,b
    /* untested !
    var sA = 0;  //source alpha  */    

    for (sy = 0; sy < sh; sy++) {
        ty = sy * scale; // y src position within target
        tY = 0 | ty;     // rounded : target pixel's y
        yIndex = 3 * tY * tw;  // line index within target array
        crossY = (tY != (0 | ty + scale)); 
        if (crossY) { // if pixel is crossing botton target pixel
            wy = (tY + 1 - ty); // weight of point within target pixel
            nwy = (ty + scale - tY - 1); // ... within y+1 target pixel
        }
        for (sx = 0; sx < sw; sx++, sIndex += 4) {
            tx = sx * scale; // x src position within target
            tX = 0 |  tx;    // rounded : target pixel's x
            tIndex = yIndex + tX * 3; // target pixel index within target array
            crossX = (tX != (0 | tx + scale));
            if (crossX) { // if pixel is crossing target pixel's right
                wx = (tX + 1 - tx); // weight of point within target pixel
                nwx = (tx + scale - tX - 1); // ... within x+1 target pixel
            }
            sR = sBuffer[sIndex    ];   // retrieving r,g,b for curr src px.
            sG = sBuffer[sIndex + 1];
            sB = sBuffer[sIndex + 2];

            /* !! untested : handling alpha !!
               sA = sBuffer[sIndex + 3];
               if (!sA) continue;
               if (sA != 0xFF) {
                   sR = (sR * sA) >> 8;  // or use /256 instead ??
                   sG = (sG * sA) >> 8;
                   sB = (sB * sA) >> 8;
               }
            */
            if (!crossX && !crossY) { // pixel does not cross
                // just add components weighted by squared scale.
                tBuffer[tIndex    ] += sR * sqScale;
                tBuffer[tIndex + 1] += sG * sqScale;
                tBuffer[tIndex + 2] += sB * sqScale;
            } else if (crossX && !crossY) { // cross on X only
                w = wx * scale;
                // add weighted component for current px
                tBuffer[tIndex    ] += sR * w;
                tBuffer[tIndex + 1] += sG * w;
                tBuffer[tIndex + 2] += sB * w;
                // add weighted component for next (tX+1) px                
                nw = nwx * scale
                tBuffer[tIndex + 3] += sR * nw;
                tBuffer[tIndex + 4] += sG * nw;
                tBuffer[tIndex + 5] += sB * nw;
            } else if (crossY && !crossX) { // cross on Y only
                w = wy * scale;
                // add weighted component for current px
                tBuffer[tIndex    ] += sR * w;
                tBuffer[tIndex + 1] += sG * w;
                tBuffer[tIndex + 2] += sB * w;
                // add weighted component for next (tY+1) px                
                nw = nwy * scale
                tBuffer[tIndex + 3 * tw    ] += sR * nw;
                tBuffer[tIndex + 3 * tw + 1] += sG * nw;
                tBuffer[tIndex + 3 * tw + 2] += sB * nw;
            } else { // crosses both x and y : four target points involved
                // add weighted component for current px
                w = wx * wy;
                tBuffer[tIndex    ] += sR * w;
                tBuffer[tIndex + 1] += sG * w;
                tBuffer[tIndex + 2] += sB * w;
                // for tX + 1; tY px
                nw = nwx * wy;
                tBuffer[tIndex + 3] += sR * nw;
                tBuffer[tIndex + 4] += sG * nw;
                tBuffer[tIndex + 5] += sB * nw;
                // for tX ; tY + 1 px
                nw = wx * nwy;
                tBuffer[tIndex + 3 * tw    ] += sR * nw;
                tBuffer[tIndex + 3 * tw + 1] += sG * nw;
                tBuffer[tIndex + 3 * tw + 2] += sB * nw;
                // for tX + 1 ; tY +1 px
                nw = nwx * nwy;
                tBuffer[tIndex + 3 * tw + 3] += sR * nw;
                tBuffer[tIndex + 3 * tw + 4] += sG * nw;
                tBuffer[tIndex + 3 * tw + 5] += sB * nw;
            }
        } // end for sx 
    } // end for sy

    // create result canvas
    var resCV = document.createElement('canvas');
    resCV.width = tw;
    resCV.height = th;
    var resCtx = resCV.getContext('2d');
    var imgRes = resCtx.getImageData(0, 0, tw, th);
    var tByteBuffer = imgRes.data;
    // convert float32 array into a UInt8Clamped Array
    var pxIndex = 0; //  
    for (sIndex = 0, tIndex = 0; pxIndex < tw * th; sIndex += 3, tIndex += 4, pxIndex++) {
        tByteBuffer[tIndex] = Math.ceil(tBuffer[sIndex]);
        tByteBuffer[tIndex + 1] = Math.ceil(tBuffer[sIndex + 1]);
        tByteBuffer[tIndex + 2] = Math.ceil(tBuffer[sIndex + 2]);
        tByteBuffer[tIndex + 3] = 255;
    }
    // writing result to canvas.
    resCtx.putImageData(imgRes, 0, 0);
    return resCV;
}
