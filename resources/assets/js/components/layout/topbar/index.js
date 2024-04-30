/////////////////////////////////////////////////////////////////////////////////////////
// "cui-topbar" module scripts

;$(document).ready(function () {
  'use strict';
  $(function() {
    $('.juzaweb__topbar__actionsDropdown .dropdown-menu').on('click', function() {
      $('.juzaweb__topbar__actionsDropdown').on('hide.bs.dropdown', function(event) {
        event.preventDefault(); // stop hiding dropdown on click

        $('.juzaweb__topbar__actionsDropdown .nav-link').on('shown.bs.tab', function(e) {
          $('.juzaweb__topbar__actionsDropdown .dropdown-toggle').dropdown('update')
        })
      })
    });

    $(document, '.juzaweb__topbar__actionsDropdown .dropdown-toggle').mouseup(function(e) {
      var dropdown = $('.juzaweb__topbar__actionsDropdown');
      var dropdownMenu = $('.juzaweb__topbar__actionsDropdownMenu');

      if (
        !dropdownMenu.is(e.target) &&
        dropdownMenu.has(e.target).length === 0 &&
        dropdown.hasClass('show')
      ) {
        dropdown.removeClass('show');
        dropdownMenu.removeClass('show');
      }
    });

    ///////////////////////////////////////////////////////////
    // livesearch scripts

    var livesearch = $('.juzaweb__topbar__livesearch');
    var close = $('.juzaweb__topbar__livesearch__close');
    var visibleClass = 'juzaweb__topbar__livesearch__visible';
    var input = $('#livesearch__input');
    var inputInner = $('#livesearch__input__inner');

    function setHidden() {
      livesearch.removeClass(visibleClass)
    }
    function handleKeyDown(e) {
      const key = event.keyCode.toString();
      if (key === '27') {
        setHidden()
      }
    }
    input.on('focus', function() {
      livesearch.addClass(visibleClass);
      setTimeout(function() {
        inputInner.focus()
      }, 200)
    });
    close.on('click', setHidden);
    document.addEventListener('keydown', handleKeyDown, false)
  });
});
