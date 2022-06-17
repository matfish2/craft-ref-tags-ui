// webpack.mix.js

let mix = require('laravel-mix');
mix.setPublicPath('src/assets/compiled');
mix.js('src/assets/js/main.js', 'ref-tags-ui.js').vue();
mix.minify('src/assets/compiled/ref-tags-ui.js')
mix.minify('src/assets/compiled/ref-tags-ui.css')
