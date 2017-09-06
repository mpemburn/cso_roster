/**
 * AjaxPost.js -- Simple wrapper for jQuery AJAX with callback
 *
 * Usage:
 * var myPost = Object.create(AjaxPost);
 * myPost.init([options]);
 *
 * options:
 * @type {
        ajaxUrl: string,    // Endpoint URL
        formSelector: string,     // Selector of form object
        callback: function  // Function called on success. Passes returned data
    }
*/

var AjaxPost = {
    formSelector: '',
    params: null,
    setupAction: function(){},
    cleanupAction: function(){},
    newAction: function(){},
    successAction: function(){},
    init: function(options) {
        $.extend(this, options);
        this._setEvents();
    },
    action: function(arg) {
        this.params = this._serialize(arg.params);
        $(this.formSelector).submit();
    },
    _setEvents: function() {
        var self = this;
        $(this.formSelector).off().on('submit', function (e) {
            var formAction = this.action + self.params;
            $.ajaxSetup({
                header: $('meta[name="_token"]').attr('content')
            });
            e.preventDefault(e);

            self.setupAction();

            $.ajax({
                type: "POST",
                url: formAction,
                data: $(this).serialize(),
                dataType: 'json',
                success: function (response) {
                    self.cleanupAction();
                    if (response.errors) {
                        var formErrors = Object.create(FormErrors);
                        formErrors.show({
                            dialog: '#error_dialog',
                            messages: '#error_messages',
                            errors: response.errors
                        });
                        return;
                    }
                    if (response.is_new) {
                        self.newAction(response.data);
                    }
                    if (response.status) {
                        self.successAction(response.data);
                    }
                },
                error: function (response) {
                    console.log(response);
                    self.cleanupAction();
                    // Warn user that the session has timed out, then reload to go to login page
                    if (response.status == '401') {
                        alert(appSpace.authTimeout)
                        location.reload();
                    }
                }
            })
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