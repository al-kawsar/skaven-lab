$(document).ready(function() {

    const loginValidator = new FormValidator('login-form', {
        validateOnKeyup: true,
        validateOnBlur: true,
        disableSubmitOnInvalid: true,
        showToastOnSubmit: true
    });

    loginValidator.addField('username', [
        ValidationRules.required,
        ValidationRules.minLength(3),
        ValidationRules.maxLength(255)
    ]);

    loginValidator.addField('password', [
        ValidationRules.required,
        ValidationRules.minLength(8),
        ValidationRules.maxLength(255)
    ]);

    $('.toggle-password').on('click', function() {
        $(this).toggleClass('feather-eye feather-eye-off');
        var input = $('#password');
        const isValid = input.hasClass('is-valid');
        const isInvalid = input.hasClass('is-invalid');

        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
        } else {
            input.attr('type', 'password');
        }

        if (isValid) {
            input.addClass('is-valid');
        }
        if (isInvalid) {
            input.addClass('is-invalid');
        }
    });

    $('#login-form').on('submit', function() {
        if (!$('#device_info').length) {
            const deviceInfo = SecurityHelper.getDeviceFingerprint();
            const hiddenField = $('<input>')
                .attr('type', 'hidden')
                .attr('name', 'device_info')
                .attr('id', 'device_info')
                .val(JSON.stringify(deviceInfo));
            $(this).append(hiddenField);
        }
    });
});