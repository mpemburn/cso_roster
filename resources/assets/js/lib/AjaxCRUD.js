/**
 * AjaxCRUD.js -- Manage full crud for AJAX-based lists
 *
 * Usage:
 * var myCRUD = Object.create(AjaxCRUD);
 * myCRUD.init([options]);
 *
 */

var AjaxCRUD = {
    ajaxGet: null,
    ajaxSave: null,
    ajaxDelete: null,
    modalForm: null,
    formSelector: null,
    addSelector: null,
    editSelector: null,
    formSelector: null,
    idSelector: null,
    listSelector: null,
    modalSelector: null,
    saveSelector: null,
    deleteSelector: null,
    submitSelector: null,
    retrieveFormUrl: null,
    retrieveListUrl: null,
    deleteDataUrl: null,
    init: function(options) {
        $.extend(this, options);
        var self = this;
        this.modalForm = Object.create(ModalForm);
        this.ajaxGet = Object.create(AjaxGet);
        this.ajaxSave = Object.create(AjaxPost);
        this.ajaxDelete = Object.create(AjaxGet);
        // Retrieve form contents functionality
        this.ajaxGet.init({
            ajaxUrl: this.retrieveFormUrl
        });
        // Create/Save functionality
        this.ajaxSave.init({
            formSelector: this.formSelector,
            successAction: function(data) {
                var retrieve = Object.create(AjaxGet);
                self.modalForm.dismiss();
                retrieve.init({
                    ajaxUrl: self.retrieveListUrl,
                    dataType: 'html'
                });
                retrieve.action({
                    params: '/' + data.member_id,
                    callback: function(data) {
                        self._refreshList(data);
                    }
                });
            },
            errorAction: function(errors) {
                var formErrors = Object.create(FormErrors);
                formErrors.show({
                    append: true,
                    messages: '#error_messages',
                    errors: errors
                });
            }
        });
        // Delete functionality
        this.ajaxDelete.init({
            ajaxUrl: self.deleteDataUrl,
            dataType: 'html'
        });
        this.ajaxDelete.setCallback(function(data) {
            self._refreshList(data);
        });

        this.modalForm.init({
            addSelector: this.addSelector,
            editSelector: this.editSelector,
            formSelector: this.formSelector,
            idSelector: this.idSelector,
            modalSelector: this.modalSelector,
            saveSelector: this.saveSelector,
            deleteSelector: this.deleteSelector,
            submitSelector: this.submitSelector,
            getAjax: this.ajaxGet,
            saveAjax: this.ajaxSave,
            deleteAjax: this.ajaxDelete,
        });
    },
    _refreshList: function(data) {
        var $htmlList = $(this.listSelector);
        $htmlList.empty();
        $htmlList.append(data);
        this.modalForm.refresh();
    }
};