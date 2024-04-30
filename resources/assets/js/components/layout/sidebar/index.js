/////////////////////////////////////////////////////////////////////////////////////////
// "cui-menu-right" module scripts
; $(document).ready(function () {
  'use strict'
  $(function () {
    /////////////////////////////////////////////////////////////////////////////////////////
    // hide non top menu related settings
    if ($('.juzaweb__menuTop').length) {
      $('.hideIfMenuTop').css({
        pointerEvents: 'none',
        opacity: 0.4,
      })
    }

    /////////////////////////////////////////////////////////////////////////////////////////
    // toggle
    $('.juzaweb__sidebar__actionToggle').on('click', function () {
      $('body').toggleClass('juzaweb__sidebar--toggled')
    })

    /////////////////////////////////////////////////////////////////////////////////////////
    // toggle theme
    $('.juzaweb__sidebar__actionToggleTheme').on('click', function () {
      var theme = document.querySelector('html').getAttribute('data-kit-theme')
      if (theme === 'dark') {
        document.querySelector('html').setAttribute('data-kit-theme', 'default')
        $('body').removeClass(
          'jw__dark juzaweb__menuLeft--gray juzaweb__menuTop--gray juzaweb__menuLeft--dark juzaweb__menuTop--dark',
        )
      }
      if (theme === 'default') {
        document.querySelector('html').setAttribute('data-kit-theme', 'dark')
        $('body').removeClass(
          'juzaweb__menuLeft--gray juzaweb__menuTop--gray juzaweb__menuLeft--dark juzaweb__menuTop--dark',
        )
        $('body').addClass('juzaweb__menuLeft--dark juzaweb__menuTop--dark')
      }
    })

    /////////////////////////////////////////////////////////////////////////////////////////
    // app name
    function updateName(name) {
      window.localStorage.setItem('appName', name)
      var el = $('.juzaweb__menuLeft').length
        ? $('.juzaweb__menuLeft__logo__name')
        : $('.juzaweb__menuTop__logo__name')
      var descr = $('.juzaweb__menuLeft').length
        ? $('.juzaweb__menuLeft__logo__descr')
        : $('.juzaweb__menuTop__logo__descr')
      el.html(name)
      if (name !== 'Clean UI Pro') {
        descr.hide()
      } else {
        descr.show()
      }
    }
    $('#appName').on('keyup', function (e) {
      var value = e.target.value
      updateName(value)
    })
    var appName = window.localStorage.getItem('appName')
    if (appName) {
      updateName(appName)
      $('#appName').val(appName)
    }

    /////////////////////////////////////////////////////////////////////////////////////////
    // set primary color
    function setPrimaryColor(color) {
      function setColor(_color) {
        window.localStorage.setItem('kit.primary', _color)
        var tag = '<style />'
        var css = `:root { --kit-color-primary: ${_color};}`
        $(tag)
          .attr('id', 'primaryColor')
          .text(css)
          .prependTo('body')
      }
      var style = $('#primaryColor')
      style ? (style.remove(), setColor(color)) : setColor(color)
    }
    var color = window.localStorage.getItem('kit.primary')
    if (color) {
      $('#colorPicker').val(color)
      setPrimaryColor(color)
      $('#resetColor')
        .parent()
        .removeClass('reset')
    }
    $('#colorPicker').on('change', function () {
      var value = $(this).val()
      setPrimaryColor(value)
      $('#resetColor')
        .parent()
        .removeClass('reset')
    })
    $('#resetColor').on('click', function () {
      window.localStorage.removeItem('kit.primary')
      $('#primaryColor').remove()
      $('#resetColor')
        .parent()
        .addClass('reset')
    })

    /////////////////////////////////////////////////////////////////////////////////////////
    // switch
    $('.juzaweb__sidebar__switch input').on('change', function () {
      var el = $(this)
      var checked = el.is(':checked')
      var to = el.attr('to')
      var setting = el.attr('setting')
      if (checked) {
        $(to).addClass(setting)
      } else {
        $(to).removeClass(setting)
      }
    })

    $('.juzaweb__sidebar__switch input').each(function () {
      var el = $(this)
      var to = el.attr('to')
      var setting = el.attr('setting')
      if ($(to).hasClass(setting)) {
        el.attr('checked', true)
      }
    })

    /////////////////////////////////////////////////////////////////////////////////////////
    // colors
    $('.juzaweb__sidebar__select__item').on('click', function () {
      var el = $(this)
      var parent = el.parent()
      var to = parent.attr('to')
      var setting = el.attr('setting')
      var items = parent.find('> div')
      var classList = ''
      items.each(function () {
        var setting = $(this).attr('setting')
        if (setting) {
          classList = classList + ' ' + setting
        }
      })
      items.removeClass('juzaweb__sidebar__select__item--active')
      el.addClass('juzaweb__sidebar__select__item--active')
      $(to).removeClass(classList)
      $(to).addClass(setting)
    })

    $('.juzaweb__sidebar__select__item').each(function () {
      var el = $(this)
      var parent = el.parent()
      var to = parent.attr('to')
      var setting = el.attr('setting')
      var items = parent.find('> div')
      if ($(to).hasClass(setting)) {
        items.removeClass('juzaweb__sidebar__select__item--active')
        el.addClass('juzaweb__sidebar__select__item--active')
      }
    })

    /////////////////////////////////////////////////////////////////////////////////////////
    // type
    $('.juzaweb__sidebar__type__items input').on('change', function () {
      var el = $(this)
      var checked = el.is(':checked')
      var to = el.attr('to')
      var setting = el.attr('setting')
      $('body').removeClass('juzaweb__menu--compact juzaweb__menu--flyout juzaweb__menu--nomenu')
      if (checked) {
        $(to).addClass(setting)
      } else {
        $(to).removeClass(setting)
      }
    })

    $('.juzaweb__sidebar__type__items input').each(function () {
      var el = $(this)
      var to = el.attr('to')
      var setting = el.attr('setting')
      if ($(to).hasClass(setting)) {
        el.attr('checked', true)
      }
    })
  })
});
