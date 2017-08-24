var FormErrors = {
    dialog: null,
    messages: null,
    errors: null,
    show: function(options) {
        $.extend(this, options);
        this._clear();
        this._listMessages();
        $(this.dialog).modal();
    },
    _clear: function() {
        $('input, select, textarea').removeClass('error');
        $(this.messages).empty();
    },
    _listMessages: function() {
        for (var field in this.errors) {
            if (this.errors.hasOwnProperty(field)) {
                $('input[name=' + field + ']').addClass('error');
                $(this.messages).append('<li class="dialog-error">' + this.errors[field] + '</li>');
            }
        }
    }
};
