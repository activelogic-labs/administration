<?php

/*
|--------------------------------------------------------------------------
| Administration Configuration
|--------------------------------------------------------------------------
|
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Application Title
    |--------------------------------------------------------------------------
    |
    | Sets the meta title for the application
    |
    */

    'title' => 'Summit System',

    /*
    |--------------------------------------------------------------------------
    | Logo
    |--------------------------------------------------------------------------
    |
    | Sets the logo on top left side of application
    |
    */

    'logo_url' => '/images/Logo@2x.jpg',

    /*
    |--------------------------------------------------------------------------
    | Styles
    |--------------------------------------------------------------------------
    |
    | Allows user to extend styles by including their own
    | We recommend using Elixir for cache busting
    |
    */

    'styles' => [
        elixir("css/app.css")
    ],

    /*
    |--------------------------------------------------------------------------
    | Scripts
    |--------------------------------------------------------------------------
    |
    | Allows user to extend scripts by including their own
    |
    */

    'scripts' => [

    ],

    /*
    |--------------------------------------------------------------------------
    | Application Users
    |--------------------------------------------------------------------------
    |
    | Defines the base URI (eg. http://localhost/admin)
    |
    */

    'base_uri' => '',

    /*
    |--------------------------------------------------------------------------
    | Application Users
    |--------------------------------------------------------------------------
    |
    | Defines the views path if the user chooses to place into a subdirectory
    |
    */

    'views_path' => '',

    /*
    |--------------------------------------------------------------------------
    |
    |--------------------------------------------------------------------------
    |
    | Sets the default controller when accessing the base route
    | Eg. \App\Http\Controllers\UsersController@listAllUsers
    |
    */

    'default_controller_route' => '\App\Http\Controllers\Dashboard@index',

    /*
    |--------------------------------------------------------------------------
    | Application Users
    |--------------------------------------------------------------------------
    |
    | Register all the controllers for the application
    |
    */

    'controllers' => [
        \App\Http\Controllers\Dashboard::class,
        \App\Http\Controllers\Policies::class,
        \App\Http\Controllers\Customers::class,
        \App\Http\Controllers\Agents::class,
        \App\Http\Controllers\Products::class,
        \App\Http\Controllers\Commissions::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Application Users
    |--------------------------------------------------------------------------
    |
    | Set which controllers belong in the navigation bar
    | Be sure to set the controller properties title and optional icon (FontAwesome)
    |
    */

    'navigation_controllers' => [
        \App\Http\Controllers\Dashboard::class,
        \App\Http\Controllers\Policies::class,
        \App\Http\Controllers\Customers::class,
        \App\Http\Controllers\Agents::class,
        \App\Http\Controllers\Products::class,
        \App\Http\Controllers\Commissions::class,
    ],

];