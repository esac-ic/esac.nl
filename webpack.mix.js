let mix = require('laravel-mix');

// Current use:
// .js for ES2017, modules etc. (modern js)
// .combine for concatenating and/or copying simple .js or .css files
// .sass for SCSS/SASS files

// Vue
mix.js('resources/assets/js/app.js', 'public/js')
   .vue({ version: 2 })
   .combine([
      "node_modules/jquery/dist/jquery.js",
      "node_modules/popper.js/dist/umd/popper.js",
      "node_modules/bootstrap/dist/js/bootstrap.js"],
      "public/js/vendor/jquery-bootstrap.js")
   .combine([
      "node_modules/datatables.net/js/jquery.dataTables.js",
      "node_modules/datatables.net-bs4/js/dataTables.bootstrap4.js",
      "node_modules/datatables.net-responsive/js/dataTables.responsive.js",
      "node_modules/datatables.net-responsive-bs4/js/responsive.bootstrap4.js",
      "resources/assets/js/datatables.js"],
      "public/js/vendor/datatables.js")
   .combine([
      "node_modules/moment/moment.js",
      "node_modules/moment/locale/nl.js"],
      "public/js/vendor/moment.js")
   .sass("resources/assets/scss/theme.scss", "public/css/app.css")
   .options({
      processCssUrls: false
   })
   .combine([
      "node_modules/datatables.net-bs4/css/dataTables.bootstrap4.css",
      "node_modules/datatables.net-responsive-bs4/css/responsive.bootstrap4.css"
   ], "public/css/vendor/datatables.css")
   .copyDirectory("resources/assets/img", "public/img");

// Management
mix.combine("node_modules/tempusdominus-bootstrap-4/build/js/tempusdominus-bootstrap-4.js", "public/js/vendor/tempusdominus.js")
   .combine("node_modules/summernote/dist/summernote-bs4.js", "public/js/vendor/summernote.js")
   .combine("node_modules/summernote/dist/summernote-bs4.css", "public/css/vendor/summernote.css")
   .combine("node_modules/tempusdominus-bootstrap-4/build/css/tempusdominus-bootstrap-4.css", "public/css/vendor/tempusdominus.css")
   .copyDirectory("node_modules/summernote/dist/font", "public/css/vendor/font");

mix.browserSync('localhost');

if (mix.inProduction()) {
   mix.version();
}

mix.options({
   processCssUrls: false,
});
