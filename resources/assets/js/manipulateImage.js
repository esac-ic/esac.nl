//Downscales the original photo to a smaller size
//file: Photo to downscale
function downscaleImage(image, ratio) {
    
    return new Promise(function (resolve, reject) {
        loadImage(image,
            function(canvas){
                canvas.toBlob(function (blob) {
                        resolve(blob);
                    }, 'image/jpeg', ratio
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

//Resizes the original photo to a thumbnail
//file: Photo to resize
function resizeImage(image, width, height, ratio) {
    return new Promise(function (resolve, reject) {
        loadImage(image,
            function(canvas){
                canvas.toBlob(function (blob) {
                        resolve(blob);
                    }, 'image/jpeg', ratio
                );
            },
            {
            maxWidth: width,
            maxHeight: height,
            minWidth: width,
            minHeight: height,
            crop: true,
            canvas: true,
            orientation: true
            }
        );
    });
}