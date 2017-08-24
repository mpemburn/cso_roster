/**
 * AddRemove.js
 *
 * AjaxCall.js -- Simple wrapper for jQuery AJAX with callback
 *
 * Usage:
 * var mySelect = Object.create(AddRemove);
 * mySelect.init([options]);
 *
 * options:
 * @type {
        ajaxUrl: string,    // Endpoint URL
        params: string,     // Parameters to pass
        callback: function  // Function called on success. Passes returned data
    }
 */

var AjaxCall = {
    ajaxUrl: '',
    params: '',
    callback: function(){},
    init: function(options) {
        $.extend(this, options);
        this.params = this._serialize(this.params);
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
                    if (response.data) {
                        self.callback(response.data);
                    }
                }
            },
            error: function (data) {
                console.log(data);
            }
        });
    },
    _serialize: function(params) {
        if (typeof(params) == 'object') {
            return $.param(params);
        } else {
            return params;
        }
    }
};