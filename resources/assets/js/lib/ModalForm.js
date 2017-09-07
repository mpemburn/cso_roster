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
        if (shouldDisable) {
           this.form.find(':input:not(:disabled)').prop('disabled',true)
        } else {
           this.form.find(':input(:disabled)').prop('disabled',false)
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
    _setEvents: function() {
        var self = this;
        var $rows = $(this.editSelector).find('[data-id]');
        var $deletes = $(this.editSelector).find('[data-delete]');
        $rows.off().on('click', function() {
            var id = $(this).attr('data-id');
            self._disableForm(false);
            self._setAction(self.saveSelector)
            self._retrieveItem(id);
        });
        $deletes.off().on('click', function(evt) {
            var id = $(this).attr('data-delete');
            evt.stopPropagation();
            self._disableForm(true);
            self._setAction(self.deleteSelector)
            self._retrieveItem(id);
            return;
        });

        $(this.addSelector).off().on('click', function() {
            self._disableForm(false);
            self.show(0);
        });

        $(this.saveSelector).off().on('click', function () {
            if (self.itemId != null) {
                self._saveItem();
            }
        });

        $(this.deleteSelector).off().on('click', function () {
            if (self.itemId != null) {
                self._deleteItem();
            }
        });

    }
};