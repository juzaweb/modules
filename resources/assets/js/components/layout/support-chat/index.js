/////////////////////////////////////////////////////////////////////////////////////////
// "chat" module scripts

;$(document).ready(function () {
  'use strict'
  $(function() {
    /////////////////////////////////////////////////////////////////////////////////////////
    // toggle
    $('.jw__chat__actionToggle').on('click', function() {
      $('body').toggleClass('jw__chat--open')
    })
  })
});
