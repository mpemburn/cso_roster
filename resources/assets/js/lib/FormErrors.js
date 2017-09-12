var FormErrors = {
    dialog: null,
    messages: null,
    append: false,
    errors: null,
    show: function(options) {
        $.extend(this, options);
        this._clear();
        this._setEvents();
        if (this.dialog != null) {
            this._listMessages();
            $(this.dialog).modal();
        }
        if (this.append) {
            this._appendMessages();
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
    _isValidEmail: function($email) {
        var regex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i;
        return regex.test($email.val());
    },
    _setEvents: function() {
        var self = this;
        $('.required').off().on('input', function() {
            var type = this.type || this.tagName.toLowerCase();
            var $this = $(this);
            var $errorMessage = $this.parent().find('.form-error');
            var isValid = ($this.val() != '');
            if (type == 'email' && isValid) {
                isValid = self._isValidEmail($this);
                if (!isValid) {
                    $errorMessage.html('Not a valid email address');
                }
            }
            $this.toggleClass('error', !isValid);
            $errorMessage.toggle(!isValid);
        })
    }
};
