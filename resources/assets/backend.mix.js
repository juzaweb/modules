const mix = require(`laravel-mix`);

const baseAsset = `vendor/juzaweb/modules/resources/assets`;
const basePublish = baseAsset + `/public`;

mix.setPublicPath(basePublish);

mix.styles([
    `${baseAsset}/js/components/vendors/bootstrap/css/card.css`,
    `${baseAsset}/js/components/vendors/bootstrap/css/utilities.css`,
    `${baseAsset}/js/components/vendors/bootstrap/css/buttons.css`,
    `${baseAsset}/js/components/vendors/bootstrap/css/table.css`,
    `${baseAsset}/js/components/vendors/bootstrap/css/typography.css`,
    `${baseAsset}/js/components/vendors/bootstrap/css/breadcrumb.css`,
    `${baseAsset}/js/components/vendors/bootstrap/css/dropdowns.css`,
    `${baseAsset}/js/components/vendors/bootstrap/css/selectboxes.css`,
    `${baseAsset}/js/components/vendors/bootstrap/css/badge.css`,
    `${baseAsset}/js/components/vendors/bootstrap/css/carousel.css`,
    `${baseAsset}/js/components/vendors/bootstrap/css/collapse.css`,
    `${baseAsset}/js/components/vendors/bootstrap/css/modal.css`,
    `${baseAsset}/js/components/vendors/bootstrap/css/alerts.css`,
    `${baseAsset}/js/components/vendors/bootstrap/css/pagination.css`,
    `${baseAsset}/js/components/vendors/bootstrap/css/navs.css`,
    `${baseAsset}/js/components/vendors/bootstrap/css/popovers.css`,
    `${baseAsset}/js/components/vendors/bootstrap/css/tooltips.css`,
    `${baseAsset}/js/components/vendors/bootstrap/css/list-group.css`,
    `${baseAsset}/js/components/vendors/bootstrap/css/progress.css`,
    `${baseAsset}/js/components/vendors/bootstrap/css/navbar.css`,
    `${baseAsset}/js/components/vendors/perfect-scrollbar/style.css`,
    `${baseAsset}/js/components/vendors/editable-table/style.css`,
    `${baseAsset}/js/components/system/auth/style.css`,
    `${baseAsset}/js/components/vendors/select2/style.css`,
    `${baseAsset}/js/components/vendors/jquery-ui/style.css`,
    `${baseAsset}/js/components/vendors/c3/style.css`,
    `${baseAsset}/js/components/core/css/core.css`,
    `${baseAsset}/js/components/core/css/measurements.css`,
    `${baseAsset}/js/components/core/css/colors.css`,
    `${baseAsset}/js/components/core/css/utils.css`,
    `${baseAsset}/js/components/styles/style.css`,
    `${baseAsset}/js/components/widgets/list/style.css`,
    `${baseAsset}/js/components/widgets/table/style.css`,
    `${baseAsset}/js/components/widgets/general/style.css`,
    `${baseAsset}/js/components/widgets/list/8/8.css`,
    `${baseAsset}/js/components/apps/style.css`,
    `${baseAsset}/js/components/layout/breadcrumbs/style.css`,
    `${baseAsset}/js/components/layout/footer/style.css`,
    `${baseAsset}/js/components/layout/menu-left/style.css`,
    `${baseAsset}/js/components/layout/menu-top/style.css`,
    `${baseAsset}/js/components/layout/sidebar/style.css`,
    `${baseAsset}/js/components/layout/topbar/style.css`,
    //`update/styles/codemirror/codemirror.css`,
    //`update/styles/codemirror/addon/hint/show-hint.css`,
], `css/backend.min.css`);

mix.combine([
    `${baseAsset}/js/components/core/index.js`,
    `${baseAsset}/js/components/layout/menu-left/index.js`,
    `${baseAsset}/js/components/layout/menu-top/index.js`,
    `${baseAsset}/js/components/layout/sidebar/index.js`,
    `${baseAsset}/js/components/layout/topbar/index.js`,
], `js/backend.min.js`);

mix.styles([
    `${baseAsset}/css/admin-bar.css`,
], 'css/admin-bar.css');
