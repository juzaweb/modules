const mix = require(`laravel-mix`);

const baseAsset = 'vendor/juzaweb/modules/resources/assets';
const basePublish = baseAsset + '/public';

mix.setPublicPath(basePublish);
mix.js(`${baseAsset}/libs/libs.js`, 'js');
//mix.sass(`${baseAsset}/libs/libs.scss`, 'css');

// Vendors
mix.styles([
    'node_modules/bootstrap/dist/css/bootstrap.css',
    `${baseAsset}/vendors/perfect-scrollbar/css/perfect-scrollbar.min.css`,
    `${baseAsset}/vendors/bootstrap-select/dist/css/bootstrap-select.min.css`,
    //`${baseAsset}/vendors/select2/dist/css/select2.min.css`,
    `node_modules/select2/dist/css/select2.min.css`,
    `${baseAsset}/vendors/bootstrap-datepicker/css/bootstrap-datepicker.min.css`,
    `${baseAsset}/vendors/sweetalert2/animate.min.css`,
    `${baseAsset}/vendors/font-awesome/css/font-awesome.min.css`,
    `${baseAsset}/vendors/bootstrap-table/bootstrap-table.min.css`,
    `${baseAsset}/vendors/toastr/toastr.min.css`,
    `${baseAsset}/vendors/nestable2/jquery.nestable.min.css`,
    `${baseAsset}/vendors/accordion/accordion.css`,
    `${baseAsset}/vendors/codemirror/codemirror.css`,
], `${basePublish}/css/vendor.min.css`);

mix.combine([
    `${basePublish}/js/libs.js`,
    `${baseAsset}/vendors/jquery-ui/jquery-ui.min.js`,
    `${baseAsset}/vendors/lazysizes/js/lazysizes.min.js`,
    `${baseAsset}/vendors/perfect-scrollbar/js/perfect-scrollbar.jquery.min.js`,
    `node_modules/select2/dist/js/select2.full.min.js`,
    `${baseAsset}/vendors/jquery-validation/jquery.validate.min.js`,
    `${baseAsset}/vendors/bootstrap-table/bootstrap-table.min.js`,
    `${baseAsset}/vendors/sweetalert2/sweetalert2.js`,
    `${baseAsset}/vendors/bootstrap-datepicker/js/bootstrap-datepicker.min.js`,
    `${baseAsset}/vendors/bootstrap-datepicker/js/load-datepicker.js`,
    `${baseAsset}/vendors/toastr/toastr.min.js`,
    `${baseAsset}/vendors/accordion/accordion.min.js`,
    `${baseAsset}/vendors/nestable2/jquery.nestable.min.js`,
    `${baseAsset}/filemanager/js/cropper.min.js`,
    `${baseAsset}/filemanager/js/dropzone.min.js`,
], `${basePublish}/js/vendor.min.js`);
