var elixir = require('laravel-elixir');

//elixir.config.sourcemaps = false;

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    var directories = {
        'node_modules/bootstrap-sass/assets/javascripts/**/*.js': 'resources/assets/js/bootstrap',
        'bower_components/typeahead.js/dist/typeahead.bundle.min.js': 'public/js/lib',
        'node_modules/jquery.dirtyforms/jquery.dirtyforms.js': 'public/js/lib'
    };
    for (directory in directories) {
        mix.copy(directory, directories[directory]);
    }
    mix.sass('app.scss', 'public/css/all.css');
    mix.scripts([
        'resources/assets/js/jquery/**/*.js',
        'resources/assets/js/lib/**/*.js',
        'resources/assets/js/page/**/*.js',
        'app.js'
    ]);
});
