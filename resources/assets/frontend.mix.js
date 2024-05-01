const mix = require(`laravel-mix`);

const baseAsset = `vendor/juzaweb/modules/resources/assets`;
const basePublish = baseAsset + `/public`;

mix.setPublicPath(basePublish);

mix.styles(
    [
        `${baseAsset}/vendors/toastr/toastr.min.css`,
    ],
    `${basePublish}/css/frontend-support.min.css`
);

mix.combine(
    [
        `${baseAsset}/vendors/toastr/toastr.min.js`,
        `${baseAsset}/js/load-ajax.js`,
        `${baseAsset}/js/helpers/helpers.js`,
        `${baseAsset}/js/helpers/recaptcha.js`,
        `${baseAsset}/js/form-ajax.js`,
    ],
    `${basePublish}/js/frontend-support.min.js`
);
