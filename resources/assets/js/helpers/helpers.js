toastr.options.timeOut = 3000;

function toastr_message(message, status, title = null) {
    if (status === true) {
        toastr.success(message, title || juzaweb.lang.successfully + ' !!');
    } else {
        toastr.error(message, title || juzaweb.lang.error + ' !!');
    }
}

function confirm_message(question, callback, title = '', type = 'warning') {
    Swal.fire({
        title: title,
        text: question,
        type: type,
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: juzaweb.lang.yes + '!',
        cancelButtonText: juzaweb.lang.cancel + '!',
    }).then((result) => {
        callback(result.value);
    });
}

function get_message_response(response)
{
    if (typeof response === 'string' || response instanceof String) {
        return {
            status: false,
            message: response
        };
    }

    // Get response message
    if (response.data) {
        if (response.data.message) {
            return {
                status: response.status,
                message: response.data.message
            };
        }
        return false;
    }

    // Get message validate
    if (response.responseJSON) {
        if (response.responseJSON.errors) {
            let message = '';
            $.each(response.responseJSON.errors, function (index, msg) {
                message = msg[0];
                return false;
            });

            return {
                status: false,
                message: message
            };
        }

        else if (response.responseJSON.message) {
            return {
                status: false,
                message: response.responseJSON.message
            };
        }
    }

    // Get message errors
    if (response.message) {
        return {
            status: false,
            message: response.message.message
        };
    }
}

function show_message(response, append = false)
{
    let msg = get_message_response(response);
    if (!msg) {
        return;
    }

    let msgHTML = `<div class="alert alert-${msg.status ? 'success' : 'danger' } jw-message">
        <button type="button" class="close" data-dismiss="alert" aria-label="${juzaweb.lang.close}">
            <span aria-hidden="true">&times;</span>
        </button>

        ${msg.status ? '<i class="fa fa-check"></i>' : '<i class="fa fa-times"></i>' } ${msg.message}
    </div>`;

    if (append) {
        $('#jquery-message').append(msgHTML);
    } else {
        $('#jquery-message').html(msgHTML);
    }
}

function show_notify(response) {
    let msg = get_message_response(response);
    if (!msg) {
        return;
    }

    toastr_message(msg.message, msg.status);
}

function htmlspecialchars(str) {
    str = String(str);
    return str.replace('&', '&amp;').replace('"', '&quot;').replace("'", '&#039;').replace('<', '&lt;').replace('>', '&gt;');
}

function toggle_global_loading(status, timeout = 300) {
    if (status) {
        $("#admin-overlay").fadeIn(300);
    } else {
        setTimeout(function(){
            $("#admin-overlay").fadeOut(300);
        }, timeout);
    }
}

function generate_uuid() {
    let d = new Date().getTime();
    let d2 = ((typeof performance !== 'undefined') && performance.now && (performance.now() * 1000)) || 0;
    return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
        let r = Math.random() * 16;
        if (d > 0) {
            r = (d + r) % 16 | 0;
            d = Math.floor(d / 16);
        } else {
            r = (d2 + r) % 16 | 0;
            d2 = Math.floor(d2 / 16);
        }
        return (c === 'x' ? r : (r & 0x3 | 0x8)).toString(16);
    });
}
