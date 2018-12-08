let mix = require("laravel-mix");

mix.browserSync('localhost');

mix.scripts([
        "node_modules/datatables.net/js/jquery.dataTables.js",
        "node_modules/datatables.net-bs4/js/dataTables.bootstrap4.js",
        "node_modules/datatables.net-responsive/js/dataTables.responsive.js",
        "node_modules/datatables.net-responsive-bs4/js/responsive.bootstrap4.js"
        ], "public/js/vendor/datatables.js")
   .scripts([
        "node_modules/moment/moment.js",
        "node_modules/moment/locale/nl.js"
        ], "public/js/vendor/moment.js");

mix.sass("resources/assets/scss/theme.scss", "public/css/app.css")
   .options({
        processCssUrls: false
   });

mix.styles([
    "node_modules/datatables.net-bs4/css/dataTables.bootstrap4.css",
    "node_modules/datatables.net-responsive-bs4/css/responsive.bootstrap4.css"
    ], "public/css/vendor/datatables.css");

mix.copyDirectory("resources/assets/img","public/img")
   .copyDirectory("node_modules/summernote/dist/font","public/css/vendor/font");

mix.copy("node_modules/jquery/dist/jquery.js", "public/js/vendor")
   .copy("node_modules/summernote/dist/summernote-bs4.js", "public/js/vendor/summernote.js")
   .copy("node_modules/summernote/dist/summernote-bs4.css", "public/css/vendor/summernote.css")
   .copy("node_modules/popper.js/dist/umd/popper.js", "public/js/vendor")
   .copy("node_modules/tempusdominus-bootstrap-4/build/css/tempusdominus-bootstrap-4.css", "public/css/vendor/tempusdominus.css")
   .copy("node_modules/tempusdominus-bootstrap-4/build/js/tempusdominus-bootstrap-4.js", "public/js/vendor/tempusdominus.js")
   .copy("node_modules/bootstrap/dist/js/bootstrap.js", "public/js/vendor")
   .copy("resources/assets/js/photoAlbum.js","public/js")
   .copy("node_modules/photoswipe/dist/photoswipe.min.js","public/js")
   .copy("node_modules/photoswipe/dist/photoswipe-ui-default.min.js","public/js")
   .copy("node_modules/blueimp-load-image/js/load-image.all.min.js","public/js");

// App js
mix.js('resources/assets/js/app.js', 'public/js');

//vuejs components
mix.js('resources/assets/vue/agenda.js','public/js');
mix.js('resources/assets/vue/zekeringen.js','public/js');

//cache busting (all copied files need to be added here)
mix.version([
    'public/js/app.js',
    'public/js/vendor/jquery.js',
    'public/js/vendor/summernote.js',
    'public/js/vendor/popper.js',
    'public/js/vendor/tempusdominus.js',
    'public/js/vendor/bootstrap.js',
    'public/css/vendor/summernote.css',
    'public/css/vendor/tempusdominus.css',
    'public/img'
]);
