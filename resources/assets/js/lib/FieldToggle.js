/* FieldToggle provides support for toggling visibility of one to several fields
 * Usage: Place in listener for the 'actor' field
 *
 var fieldToggle = Object.create(FieldToggle);
 fieldToggle.doToggle({
     toggleType: 'select',
     actorSelector: '#' + $(this).attr('id'),
     actionSelector: '.form-group.leadership-date',
     emptyValue: '0'
 });
 *
 *
 * */
var FieldToggle = {
    toggleType: null,
    actorSelector: null,
    actionSelector: null,
    emptyValue: null,
    multiAttribute: null,
    doToggle: function(options) {
        $.extend(this, options);
        switch (this.toggleType) {
            case 'checkbox':
                this._doCheckbox();
                break;
            case 'select':
                this._doSelect();
                break;
            case 'select_multi':
                this._doSelectMulti();
                break;
        }

    },
    _doCheckbox: function() {
        var $thisActor = $(this.actorSelector);
        var $thisAction = $(this.actionSelector);
        var toggle = ($thisActor.is(':checked')) ? 'show' : 'hide';
        $thisAction.removeClass('show hide');
        $thisAction.addClass(toggle);
    },
    _doSelect: function() {
        var $thisActor = $(this.actorSelector);
        var $thisAction = $(this.actionSelector);
        var toggle = ($thisActor.val() != this.emptyValue) ? 'show' : 'hide';
        $thisAction.removeClass('show hide');
        $thisAction.addClass(toggle);
    },
    _doSelectMulti: function() {
        var self = this;
        $(this.actionSelector).each(function () {
            var $this = $(this);
            var thisValue = $this.attr(self.multiAttribute);
            var currentVal = $(self.actorSelector).val();
            var toggle = (currentVal >= thisValue) ? 'show' : 'hide';
            $this.removeClass('show hide');
            $this.addClass(toggle);
        });
    }
}

