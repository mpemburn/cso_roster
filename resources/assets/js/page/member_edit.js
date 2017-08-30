// JS code for Member Edit page

$(document).ready(function ($) {

    if ($('#member_store').is('*') || $('#member_update').is('*')) {
        $('.date-pick').datepicker({
            format: 'M d, yyyy',
            orientation: 'bottom'
        });

        /* Detect any changes to the form data */
        $('#member_store, #member_update').dirtyForms()
            .on('dirty.dirtyforms clean.dirtyforms', function (ev) {
                var $submitButton = $('#submit_update');
                if (ev.type === 'dirty') {
                    $submitButton.removeAttr('disabled');
                } else {
                    $submitButton.attr('disabled', 'disabled');
                }
            });
        /* Submit form via AJAX */
        $('#member_store, #member_update').on('submit', function (e) {
            var formAction = this.action;
            $.ajaxSetup({
                header: $('meta[name="_token"]').attr('content')
            });
            e.preventDefault(e);

            $('#member_saving').removeClass('hidden');
            $('.saved').addClass('hidden');

            $.ajax({
                type: "POST",
                url: formAction,
                data: $(this).serialize(),
                dataType: 'json',
                success: function (data) {
                    var response = data.response;
                    $('#member_store, #member_update').dirtyForms('setClean');
                    $('#submit_update').attr('disabled', 'disabled');
                    $('#member_saving').addClass('hidden');
                    $('input, select, textarea').removeClass('error');
                    if (response.errors) {
                        var formErrors = Object.create(FormErrors);
                        formErrors.show({
                            dialog: '#error_dialog',
                            messages: '#error_messages',
                            errors: response.errors
                        });
                        return;
                    }
                    if (response.is_new) {
                        document.location = appSpace.baseUrl + '/member/details/' + response.member_id;
                    }
                    if (response.status) {
                        $('.saved').removeClass('hidden')
                            .show()
                            .fadeOut(3000);
                    }
                },
                error: function (response) {
                    console.log(response);
                    $('#member_store, #member_update').dirtyForms('setClean');
                    // Warn user that the session has timed out, then reload to go to login page
                    if (response.status == '401') {
                        alert(appSpace.authTimeout)
                        location.reload();
                    }
                }
            })
        });
    }
});
