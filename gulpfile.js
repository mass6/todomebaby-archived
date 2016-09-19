const elixir = require('laravel-elixir');

require('laravel-elixir-vue');

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

elixir(mix => {
    mix.styles(['core.css', 'components.css', 'colors.css', 'typeahead.css', 'custom.css'], 'public/css/theme.css')
       .webpack('app.js')
       //.scripts(['plugins/loaders/pace.min.js', 'plugins/loaders/blockui.min.js'], 'public/js/pages/login.js')
        .phpUnit();
});
