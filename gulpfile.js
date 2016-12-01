var elixir = require('laravel-elixir');

require('./elixir-extensions');

//--- Configure Elixir for the package
elixir.config.assetsPath = 'src/resources/assets';
elixir.config.publicPath = 'src/public';

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
    mix
        //Sass compilation
        .sass([
            '*.scss'
        ], 'src/public/css/administration-app.css')

        //Javascript compilation
        .scripts([
            '*.js'
        ], 'src/public/js/administration-all.js')

        //Versioning
        .version([
            'css/administration-app.css',
            'js/administration-all.js'
        ])

        .publish('php ../../../artisan vendor:publish --tag=admin-public --force')
});
