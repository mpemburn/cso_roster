$(document).ready(function ($) {

    if ($('#profile_reset').is('*') || $('#password_submit').is('*')) {
        
        // Save for Member form
        var passwordSave = Object.create(AjaxPost);
        // Save for Member form
        var passwordSave = Object.create(AjaxPost);
        passwordSave.init({
            formSelector: '#profile_reset, #password_submit',
            successAction: function(){
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
