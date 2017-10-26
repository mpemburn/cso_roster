$(document).ready(function ($) {

    if ($('#request_password_reset').is('*')) {
        
        var requestReset = Object.create(AjaxPost);
        requestReset.init({
            formSelector: '#request_password_reset',
            successAction: function(response){
                document.location = response.url;
            },
            errorAction: function(errors){
                var formErrors = Object.create(FormErrors);
                formErrors.show({
                    append: true,
                    messages: '#error_messages',
                    errors: errors
                });
            }
         });
    }
});
