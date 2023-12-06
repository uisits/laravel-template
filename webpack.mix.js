let mix = require('laravel-mix');

let mix_app_name = '/' + process.env.MIX_APP_NAME + '/';
mix.setResourceRoot(mix_app_name);

require('laravel-mix-tailwind');
const webpackConfig = require('./webpack.config')

mix.js('resources/js/app.js', 'public/js')
    .vue()
    .webpackConfig(webpackConfig)
    .postCss('resources/css/app.css', 'public/css', [
        require('tailwindcss'),
    ])
    .sourceMaps()
    .version();
