/**
 * AjaxGet.js -- Simple wrapper for jQuery AJAX with callback
 *
 * Usage:
 * var mySelect = Object.create(AjaxGet);
 * mySelect.init([options]);
 *
 * options:
 * @type {
        ajaxUrl: string,    // Endpoint URL
        params: string,     // Parameters to pass
        callback: function  // Function called on success. Passes returned data
    }
 */

var AjaxGet = {
    ajaxUrl: '',
    dataType: 'json',
    urlWithParams: '',
    params: '',
    callback: function(){},
    init: function(options) {
        $.extend(this, options);
    },
    action: function(arg) {
        this.params = this._serialize(arg.params);
        this.urlWithParams = this.ajaxUrl + this.params;
        this.callback = arg.callback;
        this._doAjax();
    },
    _doAjax: function() {
        var self = this;
        $.ajax({
            type: "GET",
            url: this.urlWithParams,
            dataType: this.dataType,
            success: function (response) {
                if (self.dataType == 'html') {
                    self.callback(response);
                    return;
                }
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