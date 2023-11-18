function addStyleSubmenu(e) {
    var t = e.find(".juzaweb__menuLeft__navigation"),
        n = e.offset().top,
        i = $(window).scrollTop(),
        o = n - i - 30,
        e = n + t.height() + 1,
        n = 60 + e - $('.juzaweb__layout').height(),
        i = $(window).height() + i - 50;

    if ((n = o < (n = i < e - n ? e - i : n) ? o : n) > 1 && n > 40) {
        t.css("margin-top", "-" + n + "px");
    } else {
        t.css("margin-top", "");
    }
}

$(function () {
    let bodyElement = $('body');

    bodyElement.on('change', '.show_on_front-change', function () {
        let showOnFront = $(this).val();

        if (showOnFront == 'posts') {
            $('.select-show_on_front').prop('disabled', true);
        }

        if (showOnFront == 'page') {
            $('.select-show_on_front').prop('disabled', false);
        }
    });

    bodyElement.on('click', '.cancel-button', function () {
        window.location = "";
    });

    bodyElement.on('change', '.generate-slug', function () {
        let title = $(this).val();

        ajaxRequest(juzaweb.adminUrl + '/load-data/generateSlug', {
            title: title
        }, {
            method: 'GET',
            callback: function (response) {
                $('input[name=slug]').val(response.slug).trigger('change');
            }
        });
    });

    bodyElement.on('click', '.slug-edit', function () {
        let slugInput = $(this).closest('.input-group').find('input:first');
        slugInput.prop('readonly', !slugInput.prop('readonly'));
    });

    bodyElement.on('click', '.close-message', function () {
        let id = $(this).data('id');
        ajaxRequest(juzaweb.adminUrl + '/remove-message', {
            id: id,
        }, {
            method: 'POST',
            callback: function (response) {

            }
        });
    });

    $(document).on('click', '.add-repeater-item', function () {
        let form = $(this).closest('.form-repeater');
        let marker = generate_uuid();
        let template = form.find('.repeater-item-template').html();
        template = replace_template(template, {marker: marker});
        form.find('.repeater-items').append(template);
    });

    $(".juzaweb__menuLeft__submenu").on("mouseover", function () {
            if (!$(this).hasClass('juzaweb__menuLeft__submenu--toggled')) {
                addStyleSubmenu($(this));
            }
        }
    );
});
