const mix = require('laravel-mix');

const baseAsset = 'vendor/juzaweb/modules/resources/assets';
const basePublish = baseAsset + '/public';

// mix.combine(
//      [
//          'modules/Backend/resources/assets/vendors/mdb.min.js',
//          'modules/Backend/resources/assets/public/monaco-editor/min/vs/loader.js',
//          'modules/Backend/resources/assets/js/appearance/editor.js',
//      ],
//     `${basePublish}/js/theme-editor.min.js`
// );
//
// mix.combine(
//     [
//         'modules/Backend/resources/assets/vendors/mdb.min.js',
//         'modules/Backend/resources/assets/public/monaco-editor/min/vs/loader.js',
//         'modules/Backend/resources/assets/js/plugin/editor.js',
//     ],
//     `${basePublish}/js/plugin-editor.min.js`
// );
//
// mix.styles(
//     [
//         'modules/Backend/resources/assets/css/appearance/editor.css',
//     ],
//     `${basePublish}/css/code-editor.min.css`
// );
//
mix.styles(
    [
        baseAsset + '/css/widget.css',
        baseAsset + '/css/media.css',
        baseAsset + '/css/menu.css',
        baseAsset + '/css/page.css',
        baseAsset + '/css/customs.css',
        baseAsset + '/css/theme.css',
        baseAsset + '/css/repeater.css',
    ],
    `${basePublish}/css/custom.min.css`
);
//
// mix.styles(
//     [
//         'modules/Backend/resources/assets/css/swagger.css',
//     ],
//     `${basePublish}/css/swagger.min.css`
// );

mix.combine(
    [
        baseAsset + '/js/load-ajax.js',
        baseAsset + '/js/recaptcha.js',
        baseAsset + '/js/helpers.js',
        baseAsset + '/js/custom-seo.js',
        baseAsset + '/js/filemanager.js',
        baseAsset + '/js/widget.js',
        baseAsset + '/js/media.js',
        baseAsset + '/js/menu.js',
        baseAsset + '/js/update.js',
        baseAsset + '/js/plugin-install.js',
        baseAsset + '/js/appearance/theme-install.js',
        baseAsset + '/js/load-select2.js',
        baseAsset + '/js/juzaweb-table.js',
        baseAsset + '/js/list-view.js',
        baseAsset + '/js/form-ajax.js',
        baseAsset + '/js/helpers/load-modal.js',
        baseAsset + '/js/helpers/number-validate.js',
        baseAsset + '/js/taxonomy.js',
        baseAsset + '/js/customs.js',
        baseAsset + '/js/setting.js',
        baseAsset + '/js/page.js',
        baseAsset + '/js/repeater.js',
    ],
    `${basePublish}/js/custom.min.js`
);
