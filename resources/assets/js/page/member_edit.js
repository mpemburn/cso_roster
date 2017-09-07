// JS code for Member Edit page

$(document).ready(function ($) {

    if ($('#member_store').is('*') || $('#member_update').is('*')) {
        $('.date-pick').datepicker({
            format: 'MM d, yyyy',
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
            retrieveFormUrl: appSpace.baseUrl + '/dues/show',
            retrieveListUrl: appSpace.baseUrl + '/member/dues',
            deleteDataUrl: appSpace.baseUrl + '/dues/delete',
        });

/*
        // CRUD for Dues payments
        var duesForm = Object.create(ModalForm);
        var duesGet = Object.create(AjaxGet);
        duesGet.init({
            ajaxUrl: appSpace.baseUrl + '/dues/show'
        });
        var duesSave = Object.create(AjaxPost);
        var duesDelete = Object.create(AjaxGet);
        duesSave.init({
            formSelector: '#update_dues',
            successAction: function(data) {
                var retrieve = Object.create(AjaxGet);
                retrieve.init({
                    ajaxUrl: appSpace.baseUrl + '/member/dues',
                    dataType: 'html'
                });
                retrieve.action({
                    params: '/' + data.member_id,
                    callback: function(data) {
                        var $duesList = $('#dues');
                        $duesList.empty();
                        $duesList.append(data);
                        duesForm.refresh();
                    }
                });
            }
        });

        duesForm.init({
            editSelector: '#dues',
            addSelector: '#add_dues',
            formSelector: '#update_dues',
            idSelector: '#dues_id',
            modalSelector: '#dues_modal',
            deleteSelector: '#dues_delete',
            saveSelector: '#dues_save',
            getAjax: duesGet,
            saveAjax: duesSave,
            deleteAjax: duesDelete,
        });
        duesDelete.init({
            ajaxUrl: appSpace.baseUrl + '/dues/delete',
            dataType: 'html'
        });
        duesDelete.setCallback(function(data) {
            var $duesList = $('#dues');
            $duesList.empty();
            $duesList.append(data);
            duesForm.refresh();
        });
*/
    }
});
