/*
 * JUZAWEB CMS 1.0 - Form Ajax support
 *
 *
 * Copyright JS Foundation and other contributors
 * Released under the MIT license
 *
 * Date: 2021-03-12T21:04Z
 */

$(function () {
    function sendMessageByResponse(response, notify = false, form = null) {
        if (notify) {
            if (typeof show_notify !== 'undefined' && typeof show_notify === 'function') {
                show_notify(response);
            }
        } else {
            if (typeof show_message !== 'undefined' && typeof show_message === 'function') {
                show_message(response, false, form);
            }
        }
    }

    function sendRequestFormAjax(form, data, btnsubmit, currentText, currentIcon, captchaToken = null) {
        let submitSuccess = form.data('success');
        let notify = form.data('notify') || false;

        if (captchaToken) {
            data.append('g-recaptcha-response', captchaToken);
        }

        $.ajax({
            type: form.attr('method'),
            url: form.attr('action'),
            dataType: 'json',
            data: data,
            cache: false,
            contentType: false,
            processData: false
        }).done(function(response) {
            sendMessageByResponse(response, notify, form);

            if (submitSuccess) {
                let handler = eval(submitSuccess)(form, response);

                if (handler === false) {
                    return false;
                }
            }

            if (response.data.redirect) {
                setTimeout(function () {
                    window.location = response.data.redirect;
                }, 1000);
                return false;
            }

            btnsubmit.find('i').attr('class', currentIcon);
            btnsubmit.prop("disabled", false);

            if (btnsubmit.data('loading-text')) {
                btnsubmit.html(currentText);
            }

            if (response.status === false) {
                return false;
            }

            return false;
        }).fail(function(response) {
            btnsubmit.find('i').attr('class', currentIcon);
            btnsubmit.prop("disabled", false);

            if (btnsubmit.data('loading-text')) {
                btnsubmit.html(currentText);
            }

            sendMessageByResponse(response, notify, form);
            return false;
        });
    }

    $(document).on('submit', '.form-ajax', function(event) {
        if (event.isDefaultPrevented()) {
            return false;
        }

        event.preventDefault();

        let form = $(this);
        let formData = new FormData(form[0]);
        let btnsubmit = form.find("button[type=submit]");
        let currentText = btnsubmit.html();
        let currentIcon = btnsubmit.find('i').attr('class');

        btnsubmit.find('i').attr('class', 'fa fa-spinner fa-spin');
        btnsubmit.prop("disabled", true);

        if (btnsubmit.data('loading-text')) {
            btnsubmit.html('<i class="fa fa-spinner fa-spin"></i> ' + btnsubmit.data('loading-text'));
        }

        if (typeof grecaptcha !== 'undefined') {
            loadRecapchaAndSubmit(
                function (token) {
                    sendRequestFormAjax(
                        form,
                        formData,
                        btnsubmit,
                        currentText,
                        currentIcon,
                        token
                    );
                }
            );
            return false;
        }

        sendRequestFormAjax(
            form,
            formData,
            btnsubmit,
            currentText,
            currentIcon
        );
    });
});
