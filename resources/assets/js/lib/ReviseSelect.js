/**
 * ReviseSelect.js
 *
 * Retrieves value from endpoint (ajaxUrl) and uses them to refresh dropdown. Useful for dataTable.js filters
 *
 * Usage:
 * var mySelect = Object.create(ReviseSelect);
 * mySelect.init([options]);
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

var ReviseSelect = {
    ajaxUrl: '',
    selector: null,
    isChild: false,
    prepend: null,
    useOriginalValues: false,
    width: null,
    dd: null,
    originalValues: [],
    params: null,
    init: function(options) {
        $.extend(this, options);
        this._setDD();
        if (this.useOriginalValues) {
            this.params = this._serialize();
        }
        this._doAjax();
    },
    _doAjax: function() {
        var self = this;
        $.ajax({
            type: "GET",
            url: this.ajaxUrl + '?' + this.params,
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    self._reloadDD(response.data);
                }
            },
            error: function (data) {
                console.log(data);
            }
        });
    },
    _reloadDD: function(data) {
        this.dd.empty();
        if (this.prepend != null) {
            this.dd.append($('<option>', {value: this.prepend.value, text: this.prepend.text}));
        }
        for (var key in data) {
            if (data.hasOwnProperty(key)) {
                var text = data[key];
                this.dd.append($('<option>', {value: key, text: text}));
            }
        }
    },
    _setDD: function() {
        var $select = $(this.selector);
        this.dd = (this.isChild) ? $select.find('select') : $select;
        if (this.width != null) {
            this.dd.css({width: this.width})
        }
    },
    _serialize: function() {
        var self = this;
        var $options = this.dd.find('option');
        $options.each(function () {
            var value = $(this).attr('value');
            if (value.length > 0) {
                self.originalValues.push(value);
            }
        });
        return (this.originalValues.length > 0) ? $.param({values: this.originalValues}) : '';
    }
};