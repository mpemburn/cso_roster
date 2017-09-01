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
    editSelector: null,
    formSelector: null,
    action: {},
    useOriginalValues: false,
    selectedItemId: 0,
    width: null,
    dd: null,
    originalValues: [],
    params: null,
    init: function(options) {
        $.extend(this, options);
        this._setListeners();
    },
    _retrieveItem: function (itemId) {
        var self = this;
        this.getAjax.action({
            params: '/' + itemId,
            callback: self._populate
        })
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
    _setListeners: function() {
        var self = this;
        var $rows = $(this.editSelector).find('[data-id]');
        $rows.on('click', function() {
            var id = $(this).attr('data-id');
            self._retrieveItem(id);
        });
    }
};