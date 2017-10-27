$(document).ready(function ($) {

    if ($('#profile_reset').is('*') || $('#password_submit').is('*')) {
        
        var profileReset = Object.create(AjaxPost);
        profileReset.init({
            formSelector: '#profile_reset, #password_submit',
            setupAction: function(){
                $('#resetting_password').removeClass('hidden');
            },
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
                 $('#resetting_password').addClass('hidden');
           }
         });
    }
});
