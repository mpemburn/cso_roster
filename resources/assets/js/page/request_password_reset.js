$(document).ready(function ($) {

    if ($('#request_password_reset').is('*')) {
        
        // Save for Member form
        var passwordSave = Object.create(AjaxPost);
        // Save for Member form
        var passwordSave = Object.create(AjaxPost);
        passwordSave.init({
            formSelector: '#request_password_reset',
            successAction: function(response){
                document.location = appSpace.baseUrl + '/profile/success';
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
