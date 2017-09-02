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
            newAction: function(){
                document.location = appSpace.baseUrl + '/member/details/' + response.member_id;
            },
            successAction: function(){
                $('.saved').removeClass('hidden')
                    .show()
                    .fadeOut(3000);
            }
         });

        var contactForm = Object.create(ModalForm);
        var contactGet = Object.create(AjaxGet);
        contactGet.init({
            ajaxUrl: appSpace.baseUrl + '/contact/show'
        });
        var contactSave = Object.create(AjaxPost);
        contactSave.init({
            formSelector: '#update_contact',
            successAction: function(data) {
                var retrieve = Object.create(AjaxGet);
                retrieve.init({
                    ajaxUrl: appSpace.baseUrl + '/member/contacts',
                    dataType: 'html'
                });
                retrieve.action({
                    params: '/' + data.member_id,
                    callback: function(data) {
                        var $contactList = $('#contacts');
                        $contactList.empty();
                        $contactList.append(data);
                        contactForm._setListeners();
                    }
                });
            }
        });

        contactForm.init({
            editSelector: '#contacts',
            modalSelector: '#contact_modal',
            saveSelector: '#contact_save',
            getAjax: contactGet,
            postAjax: contactSave,
        });

        var duesForm = Object.create(ModalForm);
        var duesGet = Object.create(AjaxGet);
        duesGet.init({
            ajaxUrl: appSpace.baseUrl + '/dues/show'
        });
        var duesSave = Object.create(AjaxPost);
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
                        duesForm._setListeners();
                    }
                });
            }
        });

        duesForm.init({
            editSelector: '#dues',
            modalSelector: '#dues_modal',
            saveSelector: '#dues_save',
            getAjax: duesGet,
            postAjax: duesSave,
        });

    }
});
