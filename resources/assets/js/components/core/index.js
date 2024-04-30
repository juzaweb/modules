/////////////////////////////////////////////////////////////////////////////////////////
// "core" module scripts

;$(document).ready(function () {
  'use strict'
  $(function() {
    /////////////////////////////////////////////////////////////////////////////////////////
    // custom scroll

    if ($('.jw__customScroll').length) {
      if (!/Mobi/.test(navigator.userAgent) && jQuery().perfectScrollbar) {
        $('.jw__customScroll').perfectScrollbar({
          theme: 'kit',
        })
      }
    }

    /////////////////////////////////////////////////////////////////////////////////////////
    // tooltips & popovers
    $('[data-toggle=tooltip]').tooltip()
    $('[data-toggle=popover]').popover()
  })
});
