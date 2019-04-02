const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css')
   .copyDirectory('resources/fonts', 'public/fonts')
   .styles([
      'resources/css/theme/normalize.min.css',
      'resources/css/theme/chartist.min.css',
      'resources/css/theme/flag-icon.min.css',
      'resources/css/theme/fullcalendar.min.css',
      'resources/css/theme/jqvmap.min.css',
      'resources/css/theme/pe-icon-7-stroke.min.css',
      'resources/css/theme/themify-icons.css',
      'resources/css/theme/weather-icons.css',
      'resources/css/theme/cs-skin-elastic.css',
      'resources/css/theme/style.css'
   ], 'public/css/all.css');
