$(document).ready(function ($) {

    if ($('#profile_reset').is('*') || $('#password_submit').is('*')) {
        
        var profileReset = Object.create(AjaxPost);
        profileReset.init({
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
