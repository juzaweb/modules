/////////////////////////////////////////////////////////////////////////////////////////
// "cui-menu-right" module scripts

;$(document).ready(function () {
  'use strict'
  $(function() {
    if ($('body').find('.juzaweb__menuTop').length < 1) {
      return
    }

    /////////////////////////////////////////////////////////////////////////////////////////
    // set active menu item on load

    var url = window.location.href
    var page = url.substr(url.lastIndexOf('/') + 1)
    var currentItem = $('.juzaweb__menuTop').find('a[href="' + page + '"]')

    console.log(page)
    currentItem
      .addClass('juzaweb__menuTop__item--active')
      .parents('.juzaweb__menuTop__submenu')
      .addClass('juzaweb__menuTop__submenu--toggled')
      .find('> .juzaweb__menuTop__navigation')
      .show()

    /////////////////////////////////////////////////////////////////////////////////////////
    // mobile toggle

    $('.juzaweb__menuTop__backdrop, .juzaweb__menuTop__mobileTrigger').on('click', function() {
      $('body').toggleClass('juzaweb__menuTop--mobileToggled')
    })

    /////////////////////////////////////////////////////////////////////////////////////////
    // mobile toggle slide

    var touchStartPrev = 0
    var touchStartLocked = false

    const unify = e => {
      return e.changedTouches ? e.changedTouches[0] : e
    }
    document.addEventListener(
      'touchstart',
      e => {
        const x = unify(e).clientX
        touchStartPrev = x
        touchStartLocked = x > 70
      },
      { passive: false },
    )
    document.addEventListener(
      'touchmove',
      e => {
        const x = unify(e).clientX
        const prev = touchStartPrev
        if (x - prev > 50 && !touchStartLocked) {
          $('body').toggleClass('juzaweb__menuTop--mobileToggled')
          touchStartLocked = true
        }
      },
      { passive: false },
    )

    $('.juzaweb__menuTop__submenu > .juzaweb__menuTop__item__link').on('click', function() {
      if ($(window).innerWidth() < 768) {
        var el = $(this).closest('.juzaweb__menuTop__submenu'),
          opened = $('.juzaweb__menuTop__submenu--toggled')

        if (
          !el.hasClass('juzaweb__menuTop__submenu--toggled') &&
          !el.parent().closest('.juzaweb__menuTop__submenu').length
        )
          opened
            .removeClass('juzaweb__menuTop__submenu--toggled')
            .find('> .juzaweb__menuTop__navigation')
            .slideUp(200)

        el.toggleClass('juzaweb__menuTop__submenu--toggled')
        var item = el.find('> .juzaweb__menuTop__navigation')
        if (item.is(':visible')) {
          item.slideUp(200)
        } else {
          item.slideDown(200)
        }
      }
    })

    // /////////////////////////////////////////////////////////////////////////////////////////
    // // set active menu item
    // var url = window.location.href
    // var page = url.substr(url.lastIndexOf('/') + 1)
    // var currentItem = $('.cui-menu-top-list-root').find('a[href="' + page + '"]')
    // currentItem.parent().toggleClass('cui-menu-top-item-active')
    // /////////////////////////////////////////////////////////////////////////////////////////
    // // add backdrop
    // $('.cui-menu-top').after('<div class="cui-menu-top-backdrop"><!-- --></div>')
    // /////////////////////////////////////////////////////////////////////////////////////////
    // // menu logic
    // $('.cui-menu-top-trigger-action').on('click', function() {
    //   $('body').toggleClass('cui-menu-top-toggled')
    // })
    // var isTabletView = false
    // function toggleMenu() {
    //   if (!isTabletView) {
    //     $('body').addClass('cui-menu-top-toggled')
    //   }
    // }
    // if ($(window).innerWidth() <= 992) {
    //   toggleMenu()
    //   isTabletView = true
    // }
    // $(window).on('resize', function() {
    //   if ($(window).innerWidth() <= 992) {
    //     toggleMenu()
    //     isTabletView = true
    //   } else {
    //     isTabletView = false
    //   }
    // })
    // $('.cui-menu-top-handler, .cui-menu-top-backdrop').on('click', function() {
    //   $('body').toggleClass('cui-menu-top-toggled-mobile')
    // })
    // /////////////////////////////////////////////////////////////////////////////////////////
    // // submenu
    // $('.cui-menu-top-submenu > a').on('click', function() {
    //   if ($('body').find('.cui-menu-top').length && $(window).innerWidth() < 768) {
    //     var parent = $(this).parent(),
    //       opened = $('.cui-menu-top-submenu-toggled')
    //     if (
    //       !parent.hasClass('cui-menu-top-submenu-toggled') &&
    //       !parent.parent().closest('.cui-menu-top-submenu').length
    //     )
    //       opened
    //         .removeClass('cui-menu-top-submenu-toggled')
    //         .find('> .cui-menu-top-list')
    //         .slideUp(200)
    //     parent.toggleClass('cui-menu-top-submenu-toggled')
    //     var item = parent.find('> .cui-menu-top-list')
    //     if (item.is(':visible')) {
    //       item.slideUp(200)
    //     } else {
    //       item.slideDown(200)
    //     }
    //   }
    // })
  })
});
