var FormErrors = {
    dialog: null,
    messages: null,
    append: false,
    emailSelector: null,
    errors: null,
    show: function(options) {
        $.extend(this, options);
        this._clear();
        if (this.dialog != null) {
            this._listMessages();
            $(this.dialog).modal();
        }
        if (this.append) {
            this._appendMessages();
        }
        if (this.emailSelector != null) {
            this._setEmailListener();
        }
    },
    _clear: function() {
        $('input, select, textarea').removeClass('error');
        $('.form-error').remove();
        $(this.messages).empty();
    },
    _listMessages: function() {
        for (var field in this.errors) {
            if (this.errors.hasOwnProperty(field)) {
                $('input[name=' + field + '], select[name=' + field + ']').addClass('error');
                $(this.messages).append('<li class="dialog-error">' + this.errors[field] + '</li>');
            }
        }
    },
    _appendMessages: function() {
        for (var field in this.errors) {
            if (this.errors.hasOwnProperty(field)) {
                var $field = $('input[name=' + field + '], select[name=' + field + ']');
                var $parent = $field.parent();
                $field.addClass('error');
                $parent.append('<div class="form-error">' + this.errors[field] + '</div>');
            }
        }
    },
    _setEmailListener: function() {
        var self = this;
        $(this.emailSelector).off().on('input', function() {
            var regex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i;
            var $email = $(self.emailSelector);
            var $errorMessage = $email.parent().find('.form-error');
            var isValid = regex.test($email.val());
            $email.toggleClass('error', !isValid);
            $errorMessage.toggle(!isValid);
        })
    }
};
