/**
 * ModalForm.js
 *
 * Retrieves value from endpoint (ajaxUrl) and uses them to refresh dropdown. Useful for dataTable.js filters
 *
 * Usage:
 * var myForm = Object.create(ModalForm);
 * myForm.init([options]);
 *
 * options:
 * @type {
        ajaxUrl: string,    // Endpoint URL
        selector: string,   // Selector for dropdown
        isChild: boolean,   // If dropdown is child of selector (above), use .find('select')
        prepend: Object,    // <option> to prepend to dropdown (e.g., {value: '0', text: 'Select'}
        useOriginalValues: boolean, // Use only the values that were originally in the dropdown
        width: string       // Set width of dropdown
    }
 */

var ModalForm = {
    ajaxUrl: '',
    getAjax: null,
    saveAjax: null,
    deleteAjax: null,
    addSelector: null,
    editSelector: null,
    formSelector: null,
    idSelector: null,
    modalSelector: null,
    saveSelector: null,
    deleteSelector: null,
    submitSelector: null,
    cancelSelector: null,
    itemId: null,
    form: null,
    modal: null,
    action: {},
    useOriginalValues: false,
    selectedItemId: 0,
    width: null,
    dd: null,
    originalValues: [],
    params: null,
    init: function(options) {
        $.extend(this, options);
        this.form = $(this.formSelector);
        this._setEvents();
    },
    refresh: function() {
        this._setEvents();
    },
    show: function(itemId) {
        this.itemId = itemId;
        this._setAction(this.saveSelector)
        this._clearForm(itemId);
        this._setEvents()
        this._initModal();
    },
    dismiss: function() {
        this.modal.modal('hide');
    },
    _clearForm: function(itemId) {
        this.form[0].reset();
        if (typeof(itemId) != 'undefined' && this.idSelector != null) {
            $(this.idSelector).val(itemId);
        }
    },
    _deleteItem: function() {
        this.deleteAjax.action({
            params: '/' + this.itemId
        });
    },
    _disableForm: function(shouldDisable) {
        var $body = $('.modal-body');
        if (shouldDisable) {
           this.form.find(':input:not(:disabled)').prop('disabled',true)
           $body.addClass('grayed');
        } else {
           this.form.find(':input(:disabled)').prop('disabled',false)
           $body.removeClass('grayed');
        }
    },
    _initModal: function() {
        this.modal = $(this.modalSelector);
        this.modal.modal();
    },
    _populate: function(data) {
        for (var key in data) {
            if (data.hasOwnProperty(key)) {
                var value = (data[key] != null) ? data[key] : '';
                var $field = $('#' + key);
                if ($field.length > 0) {
                    $field.val(value);
                }
            }
        }
    },
    _retrieveItem: function (itemId) {
        var self = this;
        this.itemId = itemId;
        this.getAjax.action({
            params: '/' + itemId,
            callback: self._populate
        });
        this._initModal();
    },
    _saveItem: function() {
        this.saveAjax.action({
            params: '/' + this.itemId
        });
    },
    _setAction: function(action) {
        $(this.saveSelector + ', ' + this.deleteSelector).hide();
        $(action).show();
    },
    _setTitle: function(verb) {
        if ($('.modal_action').is('*')) {
            var $titleAction = $('.modal_action');
            $titleAction.empty();
            $titleAction.html(verb + " ");
        }
    },
    _setEvents: function() {
        var self = this;
        var $edits = $(this.editSelector).find('[data-id]');
        var $deletes = $(this.editSelector).find('[data-delete]');
        // When pencil is clicked, retrieve form data and present modal for editing
        $edits.off().on('click', function() {
            var id = $(this).attr('data-id');
            self._setTitle('Edit');
            self._disableForm(false);
            self._setAction(self.saveSelector)
            self._retrieveItem(id);
        });
        // When 'x' is clicked, retrieve form data and present modal for deleting
        $deletes.off().on('click', function(evt) {
            var id = $(this).attr('data-delete');
            evt.stopPropagation();
            self._setTitle('Delete');
            self._disableForm(true);
            self._setAction(self.deleteSelector)
            self._retrieveItem(id);
            return;
        });

        // Create an item
        $(this.addSelector).off().on('click', function() {
            self._setTitle('New');
            self._disableForm(false);
            self.show(0);
        });

        // Update the item
        $(this.saveSelector).off().on('click', function () {
            if (self.itemId != null) {
                self._saveItem();
            }
        });

        // Delete the item
        $(this.deleteSelector).off().on('click', function () {
            if (self.itemId != null) {
                self._deleteItem();
            }
        });

        // "Clean" the form when the cancel button is clicked
        $(this.cancelSelector).off().on('click', function () {
            self.form.dirtyForms('setClean')
        });

         // Detect any changes to the form data
        $(this.formSelector).dirtyForms()
         .on('dirty.dirtyforms clean.dirtyforms', function (ev) {
             var $submitButton = $(self.submitSelector);
             if (ev.type === 'dirty') {
                 $submitButton.removeAttr('disabled');
             } else {
                 $submitButton.attr('disabled', 'disabled');
             }
         });
    }
};