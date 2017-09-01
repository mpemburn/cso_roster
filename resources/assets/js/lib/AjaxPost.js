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
    setupAction: function(){},
    cleanupAction: function(){},
    newAction: function(){},
    successAction: function(){},
    init: function(options) {
        $.extend(this, options);
        this._setListener();
    },
    _setListener: function() {
        var self = this;
        $(this.formSelector).on('submit', function (e) {
            var formAction = this.action;
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
                success: function (data) {
                    var response = data.response;
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
                        self.newAction();
                    }
                    if (response.status) {
                        self.successAction();
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
    }
};