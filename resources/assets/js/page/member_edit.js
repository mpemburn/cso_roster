// JS code for Member Edit page

$(document).ready(function ($) {

    if ($('#member_store').is('*') || $('#member_update').is('*')) {
        // Set date pickers
        $('.date-pick').datepicker({
            format: 'MM d, yyyy',
            orientation: 'bottom'
        });
        $('.date-pick-short').datepicker({
            format: 'mm/dd/yyyy',
            orientation: 'bottom'
        }).on('show', function(evt) {
            $(this).datepicker('update');
        });

         // Detect any changes to the form data
         $('#member_store, #member_update').dirtyForms()
             .on('dirty.dirtyforms clean.dirtyforms', function (ev) {
                 var $submitButton = $('#submit_update');
                 if (ev.type === 'dirty') {
                     $submitButton.removeAttr('disabled');
                 } else {
                     $submitButton.attr('disabled', 'disabled');
                 }
             });

        // Create/Save for Member form
        var memberSave = Object.create(AjaxPost);
        memberSave.init({
            formSelector: '#member_store, #member_update',
            setupAction: function(){
                $('#member_saving').removeClass('hidden');
                $('.saved').addClass('hidden');
            },
            cleanupAction: function(){
                $('#member_store, #member_update').dirtyForms('setClean');
                $('#submit_update').attr('disabled', 'disabled');
                $('#member_saving').addClass('hidden');
                $('input, select, textarea').removeClass('error');
            },
            newAction: function(data){
                document.location = appSpace.baseUrl + '/member/details/' + data.member_id;
            },
            successAction: function(){
                $('.saved').removeClass('hidden')
                    .show()
                    .fadeOut(3000);
            },
            errorAction: function(errors){
                $('#member_saving').addClass('hidden');
                var formErrors = Object.create(FormErrors);
                formErrors.show({
                    append: true,
                    messages: '#error_messages',
                    errors: errors
                });
            }
         });

        // CRUD for Contacts
        var contactsCRUD = Object.create(AjaxCRUD);
        contactsCRUD.init({
            formSelector: '#update_contact',
            addSelector: '#add_contact',
            editSelector: '#contacts',
            idSelector: '#contact_id',
            listSelector: '#contacts',
            modalSelector: '#contact_modal',
            saveSelector: '#contact_save',
            deleteSelector: '#contact_delete',
            submitSelector: '#contact_save',
            cancelSelector: '#contact_cancel',
            retrieveFormUrl: appSpace.baseUrl + '/contact/show',
            retrieveListUrl: appSpace.baseUrl + '/member/contacts',
            deleteDataUrl: appSpace.baseUrl + '/contact/delete',
        });

         // CRUD for Dues payments
        var duesCRUD = Object.create(AjaxCRUD);
        duesCRUD.init({
            formSelector: '#update_dues',
            addSelector: '#add_dues',
            editSelector: '#dues',
            idSelector: '#dues_id',
            listSelector: '#dues',
            modalSelector: '#dues_modal',
            saveSelector: '#dues_save',
            deleteSelector: '#dues_delete',
            submitSelector: '#dues_save',
            cancelSelector: '#dues_cancel',
            retrieveFormUrl: appSpace.baseUrl + '/dues/show',
            retrieveListUrl: appSpace.baseUrl + '/member/dues',
            deleteDataUrl: appSpace.baseUrl + '/dues/delete',
        });

        // CRUD for Board Roles
        var rolesCRUD = Object.create(AjaxCRUD);
        rolesCRUD.init({
            formSelector: '#update_role',
            addSelector: '#add_role',
            editSelector: '#board_roles',
            idSelector: '#roles_id',
            listSelector: '#board_roles',
            modalSelector: '#role_modal',
            saveSelector: '#role_save',
            deleteSelector: '#role_delete',
            submitSelector: '#role_save',
            cancelSelector: '#role_cancel',
            retrieveFormUrl: appSpace.baseUrl + '/role/show',
            retrieveListUrl: appSpace.baseUrl + '/member/roles',
            deleteDataUrl: appSpace.baseUrl + '/role/delete',
        });
    }
});
