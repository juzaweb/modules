$(function () {
    $(document).on('keypress', '.is-number', function () {
        return validate_isNumberKey(this);
    });

    $(document).on('keyup', '.number-format', function () {
        return validate_FormatNumber(this);
    });

    function validate_isNumberKey(evt) {
        let charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode == 59 || charCode == 46)
            return true;

        return !(charCode > 31 && (charCode < 48 || charCode > 57));
    }

    function validate_FormatNumber(a) {
        a.value = a.value.replace(/\,/gi, "");
        a.value = a.value.replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
    }
});