var elixir = require('laravel-elixir');

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
        ])

        //Javascript compilation
        .scripts([
            '*.js'
        ])

        //Versioning
        .version([
            'css/app.css',
            'js/all.js'
        ])

        //Live reload
        .browserSync({
            proxy: 'lp.dev'
        });
});