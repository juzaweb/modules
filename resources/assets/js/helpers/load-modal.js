$(function () {
    $(document).on('click', '.load-modal', function(event) {
        if (event.isDefaultPrevented()) {
            return false;
        }

        event.preventDefault();
        let data = $(this).data();
        let btnsubmit = $(this);
        let currentIcon = btnsubmit.find('i').attr('class');

        btnsubmit.find('i').attr('class', 'fa fa-spinner fa-spin');
        btnsubmit.prop("disabled", true);
        btnsubmit.addClass("disabled");

        let query_str = '';
        $.each(data, function (index, item) {
            if (index !== 'url') {
                query_str += '&'+index+'='+item;
            }
        });

        let url = $(this).data('url');

        if (query_str) {
            url = url + "?"+query_str;
        }

        $.ajax({
            type: 'GET',
            url: url,
            dataType: 'json',
            data: {},
            cache:false,
            contentType: false,
            processData: false
        }).done(function(response) {

            btnsubmit.find('i').attr('class', currentIcon);
            btnsubmit.prop("disabled", false);
            btnsubmit.removeClass("disabled");

            if (response.status === false) {
                return false;
            }

            $('#show-modal').html(response.data.source);
            $('#show-modal .modal').modal();

            return false;
        }).fail(function(response) {
            btnsubmit.find('i').attr('class', currentIcon);
            btnsubmit.prop("disabled", false);

            /*show_message(response);*/
            return false;
        });
    });
});