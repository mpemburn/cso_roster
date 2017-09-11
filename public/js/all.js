/**
 * jquery.clearable.js
 *
 * Adapted from http://stackoverflow.com/questions/6258521/clear-icon-inside-input-text
 * Requires the following CSS:
    .clearable {
        background: #fff url(data:image/gif;base64,R0lGODlhBwAHAIAAAP///5KSkiH5BAAAAAAALAAAAAAHAAcAAAIMTICmsGrIXnLxuDMLADs=) no-repeat right -10px center;
        border: 1px solid #999;
        padding: 3px 24px 3px 4px; // Second value must match settings.offset
        border-radius: 3px;
    }
    .clearable.x  { background-position: right 10px center; }
    .clearable.onX{ cursor: pointer; }
    .clearable::-ms-clear {display: none; width:0; height:0;}

*/

(function ($) {
    $.fn.clearable = function (options) {
        var settings = $.extend({
            animate: false,
            offset: 24, // Pixels of offset from right end of input
            // Optional callbacks
            onInput: function() {},
            onClear: function() {}
        }, options);

        // Add class to target if not present
        this.addClass('clearable');
        // Events
        this.on('input', function (evt) {
            if (settings.animate) {
                $(this).css({ transition: 'background 0.4s' })
            }
            $(this)[_toggle(this.value)]('x');
            settings.onInput($(this));
        }).on('mousemove', function(evt){
            $(this)[_toggle(_isOnX(evt))]('onX');
        }).on('touchstart click', function(evt){
            evt.preventDefault();
            if (_isOnX(evt)) {
                settings.onClear($(this));
                $(this).removeClass('x onX').val('').change();
            }
        });

        // Determine whether user's mouse is over the 'X'
        function _isOnX(evt) {
            return (evt.target.offsetWidth - settings.offset) < (evt.clientX - evt.target.getBoundingClientRect().left);
        }

        // Add or remove classes based on value
        function _toggle(value) {
            return value ? 'addClass' : 'removeClass';
        }

        return this;
    };


}(jQuery));
/**
 * AddColumnFilters.js
 *
 * Adds dropdown filters to a dataTables.js table
 *
 * @type {
        dataTables: Object // the instance of dataTables
    }
 */
var AddColumnFilters = {
    dataTables: null,
    init: function(options) {
        $.extend(this, options);
        this._doAdd();
    },
    _doAdd: function() {
        this.dataTables.api().columns('.filterable').every(function (index) {
            var column = this;
            var header = column.header();
            var select = $('<select><option value="">' + header.innerHTML + '</option></select>')
                .appendTo($(column.header()).empty())
                .on('change', function () {
                    var val = $.fn.dataTable.util.escapeRegex(
                        $(this).val()
                    );

                    column
                        .search(val ? '^' + val + '$' : '', true, false)
                        .draw();
                });
            // Generate dropdown options from column contents.  Replace the blank value (if present) with "All"
            column.data().unique().sort().each(function (d, j) {
                var label = (d == '') ? 'All' : d;
                select.append('<option value="' + d + '">' + label + '</option>')
            });

        });
    }
}
/**
 * AjaxCRUD.js -- Manage full crud for AJAX-based lists
 *
 * Usage:
 * var myCRUD = Object.create(AjaxCRUD);
 * myCRUD.init([options]);
 *
 */

var AjaxCRUD = {
    ajaxGet: null,
    ajaxSave: null,
    ajaxDelete: null,
    modalForm: null,
    formSelector: null,
    addSelector: null,
    editSelector: null,
    formSelector: null,
    idSelector: null,
    listSelector: null,
    modalSelector: null,
    saveSelector: null,
    deleteSelector: null,
    submitSelector: null,
    cancelSelector: null,
    retrieveFormUrl: null,
    retrieveListUrl: null,
    deleteDataUrl: null,
    init: function(options) {
        $.extend(this, options);
        var self = this;
        this.modalForm = Object.create(ModalForm);
        this.ajaxGet = Object.create(AjaxGet);
        this.ajaxSave = Object.create(AjaxPost);
        this.ajaxDelete = Object.create(AjaxGet);
        // Retrieve form contents functionality
        this.ajaxGet.init({
            ajaxUrl: this.retrieveFormUrl
        });
        // Create/Save functionality
        this.ajaxSave.init({
            formSelector: this.formSelector,
            successAction: function(data) {
                var retrieve = Object.create(AjaxGet);
                self.modalForm.dismiss();
                retrieve.init({
                    ajaxUrl: self.retrieveListUrl,
                    dataType: 'html'
                });
                retrieve.action({
                    params: '/' + data.member_id,
                    callback: function(data) {
                        self._refreshList(data);
                    }
                });
            },
            errorAction: function(errors) {
                var formErrors = Object.create(FormErrors);
                formErrors.show({
                    append: true,
                    messages: '#error_messages',
                    errors: errors
                });
            }
        });
        // Delete functionality
        this.ajaxDelete.init({
            ajaxUrl: self.deleteDataUrl,
            dataType: 'html'
        });
        this.ajaxDelete.setCallback(function(data) {
            self._refreshList(data);
        });

        this.modalForm.init({
            addSelector: this.addSelector,
            editSelector: this.editSelector,
            formSelector: this.formSelector,
            idSelector: this.idSelector,
            modalSelector: this.modalSelector,
            saveSelector: this.saveSelector,
            deleteSelector: this.deleteSelector,
            submitSelector: this.submitSelector,
            cancelSelector: this.cancelSelector,
            getAjax: this.ajaxGet,
            saveAjax: this.ajaxSave,
            deleteAjax: this.ajaxDelete,
        });
    },
    _refreshList: function(data) {
        var $htmlList = $(this.listSelector);
        $htmlList.empty();
        $htmlList.append(data);
        this.modalForm.refresh();
    }
};
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
        if (typeof(arg.callback) == 'function') {
            this.callback = arg.callback;
        }
        this._doAjax();
    },
    setCallback: function(callback) {
        this.callback = callback;
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
    errorAction: function(){},
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
        $(this.formSelector).on('submit', function (e) {
            var params = (self.params == null) ? '' : self.params;
            var formAction = this.action + params;
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
                    if (response.errors) {
                        self.errorAction(response.errors);
                        return;
                    }
                    if (response.is_new) {
                        self.newAction(response.data);
                    }
                    if (response.status) {
                        self.cleanupAction();
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


var FormErrors = {
    dialog: null,
    messages: null,
    append: false,
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
    }
};

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
    getAjax: null,
    saveAjax: null,
    deleteAjax: null,
    addSelector: null,
    editSelector: null,
    formSelector: null,
    idSelector: null,
    modalSelector: null,
    saveSelector: null,
    deleteSelector: null,
    submitSelector: null,
    cancelSelector: null,
    itemId: null,
    form: null,
    modal: null,
    action: {},
    useOriginalValues: false,
    selectedItemId: 0,
    width: null,
    dd: null,
    originalValues: [],
    params: null,
    init: function(options) {
        $.extend(this, options);
        this.form = $(this.formSelector);
        this._setEvents();
    },
    refresh: function() {
        this._setEvents();
    },
    show: function(itemId) {
        this.itemId = itemId;
        this._setAction(this.saveSelector)
        this._clearForm(itemId);
        this._setEvents()
        this._initModal();
    },
    dismiss: function() {
        this.modal.modal('hide');
    },
    _clearForm: function(itemId) {
        this.form[0].reset();
        if (typeof(itemId) != 'undefined' && this.idSelector != null) {
            $(this.idSelector).val(itemId);
        }
    },
    _deleteItem: function() {
        this.deleteAjax.action({
            params: '/' + this.itemId
        });
    },
    _disableForm: function(shouldDisable) {
        var $body = $('.modal-body');
        if (shouldDisable) {
           this.form.find(':input:not(:disabled)').prop('disabled',true)
           $body.addClass('grayed');
        } else {
           this.form.find(':input(:disabled)').prop('disabled',false)
           $body.removeClass('grayed');
        }
    },
    _initModal: function() {
        this.modal = $(this.modalSelector);
        this.modal.modal();
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
    _retrieveItem: function (itemId) {
        var self = this;
        this.itemId = itemId;
        this.getAjax.action({
            params: '/' + itemId,
            callback: self._populate
        });
        this._initModal();
    },
    _saveItem: function() {
        this.saveAjax.action({
            params: '/' + this.itemId
        });
    },
    _setAction: function(action) {
        $(this.saveSelector + ', ' + this.deleteSelector).hide();
        $(action).show();
    },
    _setTitle: function(verb) {
        if ($('.modal_action').is('*')) {
            var $titleAction = $('.modal_action');
            $titleAction.empty();
            $titleAction.html(verb + " ");
        }
    },
    _setEvents: function() {
        var self = this;
        var $edits = $(this.editSelector).find('[data-id]');
        var $deletes = $(this.editSelector).find('[data-delete]');
        // When pencil is clicked, retrieve form data and present modal for editing
        $edits.off().on('click', function() {
            var id = $(this).attr('data-id');
            self._setTitle('Edit');
            self._disableForm(false);
            self._setAction(self.saveSelector)
            self._retrieveItem(id);
        });
        // When 'x' is clicked, retrieve form data and present modal for deleting
        $deletes.off().on('click', function(evt) {
            var id = $(this).attr('data-delete');
            evt.stopPropagation();
            self._setTitle('Delete');
            self._disableForm(true);
            self._setAction(self.deleteSelector)
            self._retrieveItem(id);
            return;
        });

        // Create an item
        $(this.addSelector).off().on('click', function() {
            self._setTitle('New');
            self._disableForm(false);
            self.show(0);
        });

        // Update the item
        $(this.saveSelector).off().on('click', function () {
            if (self.itemId != null) {
                self._saveItem();
            }
        });

        // Delete the item
        $(this.deleteSelector).off().on('click', function () {
            if (self.itemId != null) {
                self._deleteItem();
            }
        });

        // "Clean" the form when the cancel button is clicked
        $(this.cancelSelector + ', button.close').off().on('click', function () {
            self.form.dirtyForms('setClean')
        });

         // Detect any changes to the form data
        $(this.formSelector).dirtyForms()
         .on('dirty.dirtyforms clean.dirtyforms', function (ev) {
             var $submitButton = $(self.submitSelector);
             if (ev.type === 'dirty') {
                 $submitButton.removeAttr('disabled');
             } else {
                 $submitButton.attr('disabled', 'disabled');
             }
         });
    }
};
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
/**
 * TableManager.js
 *
 * Supports DataTable.js table with Twitter typeahead.js search field.
 * Features support for Add and Remove functions via AJAX.
 *
 @type{
        typeaheadUrl: string, // URL to retrieve suggestions for typeahead field
        addUrl: string,  // URL for adding to table via "Add" button
        removeUrl: string, // URL for removing from table via "X" remove icons
        idName: string, // Name of ID field for each table row
        tableSelector: string, // Selector of the table
        searchSelector: string, // Selector of the search input field
        addSelector: string, // Selector of the Add button
        removeSelector: string // Select of the Remove icons
        timeoutMessage: string // Alert message sent to user if session has timed out.
        canEdit: boolean // Enables editing features if true
    }
 */
var TableManager = {
    typeaheadUrl: '',
    addUrl: '',
    removeUrl: '',
    idName: '',
    tableSelector: '',
    searchSelector: '',
    addSelector: '',
    removeSelector: '',
    timeoutMessage: 'Your session has expired and you have been logged out',
    canEdit: false,
    table: null,
    search: null,
    add: null,
    remove: null,
    bloodhound: null,
    onTableComplete: function () {
    },
    ajaxCallback: function () {
    },
    init: function (options) {
        $.extend(this, options);
        this._setTable();
        if (this.canEdit) {
            this._setBloodhound();
            this._setTypeahead();
            this._setListeners();
        }
    },
    _doAjax: function (url, idParam, ajaxCallback) {
        this.ajaxCallback = ajaxCallback;
        var self = this;
        $.ajax({
            type: "GET",
            url: url + '&' + idParam,
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    if (response.data) {
                        self.ajaxCallback(response.data);
                    }
                }
            },
            error: function (response) {
                console.log(response);
                if (response.status == '401') {
                    alert(self.timeoutMessage)
                    location.reload();
                }
            }
        });
    },
    _onAddComplete: function (data) {
        var newRow = this.table.row.add([
                data.name,
                data.phone,
                '<a href="mailto:' + data.email + '">' + data.email + '</a>',
                data.coven,
                '<i class="fa fa-close guild-remove"></i>'
            ])
            .draw()
            .node();
        // Add the data-id attribute to the newly created row
        $(newRow).attr('data-id', data.member_id);
        // Add the 'remove' icon
        var $remove = $(newRow).find('i').parent();
        $remove.addClass('remove');
        // Refresh remove listener
        this._setListenerRemove();
        // Disable add button and clear search field
        this.search.typeahead('val', '');
        this.add.attr('disabled', 'disabled');
    },
    _onRemoveComplete: function () {

    },
    _setBloodhound: function () {
        var self = this;
        this.bloodhound = new Bloodhound({
            datumTokenizer: function (datum) {
                return Bloodhound.tokenizers.whitespace(datum.value);
            },
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            remote: {
                wildcard: '%QUERY',
                url: self.typeaheadUrl,
                transform: function (response) {
                    // Populate typeahead list with returned data
                    return $.map(response, function (data) {
                        return {
                            id: data.id,
                            value: data.value
                        };
                    });
                }
            }
        });
    },
    _setListeners: function () {
        this._setListenerAdd();
        this._setListenerRemove();
        this._timeoutListener();
    },
    _setListenerAdd: function () {
        var self = this;
        this.add = $(this.addSelector);
        this.add.on('click', function () {
            var idValue = self.search.data(self.idName);
            self._doAjax(self.addUrl, self.idName + '=' + idValue, self._onAddComplete);
        });
    },
    _setListenerRemove: function () {
        var self = this;
        this.remove = $(this.removeSelector);
        this.remove.off().on('click', function (evt) {
            evt.stopPropagation();
            var $row = $(this).closest('tr');
            var idValue = $row.attr('data-id');
            $row.hide();
            self.table
                .row($(this).parents('tr'))
                .remove()
                .draw();
            self._doAjax(self.removeUrl, self.idName + '=' + idValue, self._onRemoveComplete);
        });
    },
    _setTable: function () {
        var self = this;
        this.table = $(this.tableSelector).DataTable({
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]], // Number of entries to show
            iDisplayLength: -1,
            aaSorting: [],
            columnDefs: [
                {orderable: false, targets: (self.canEdit) ? 4 : 3}
            ],
            fnDrawCallback: function () {
                // Hide pagination buttons of only one page is showing
                var $paginates = $('.dataTables_paginate').find('.paginate_button');
                $('.dataTables_paginate').toggle($paginates.length > 3);
            },
            initComplete: function () {
                var $search = $($(this).selector + '_filter').find('input[type="search"]');
                // Add 'clearable' x to search field, and callback to restore table on clear
                $search.clearable({
                    onClear: function () {
                        self.table.search('').columns().search('').draw();
                    }
                });
                // Add filter dropdowns to dataTables.js header
                var addFilters = Object.create(AddColumnFilters);
                addFilters.init({dataTables: this});
                // Do callback
                self.onTableComplete();
            }
        });
    },
    _setTypeahead: function () {
        var self = this;
        this.search = $(this.searchSelector);
        this.search.typeahead(null, {
            name: 'id',
            display: 'value',
            source: this.bloodhound,
            hint: true,
            highlight: true,
            limit: Infinity,
        }).on('typeahead:selected', function (evt, data) {
            self.search.data(self.idName, data.id);
            self.add.removeAttr('disabled');
        }).on('input', function () {
            if ($(this).val() == '') {
                self.add.attr('disabled', 'disabled');
            }
        }).clearable({
            onClear: function (target) {
                // Clear typeahead and disable the add button
                target.typeahead('val', '');
                self.add.attr('disabled', 'disabled');
            }
        });
    },
    _timeoutListener: function(){
        /* This is a workaround for Bloodhound's lack of error handling.
           It attempts to send a call to the 'typeaheadUrl'.
           If the session has timed out, it will return a 401 'Unauthorized' error.
           This will alert the user, then return them to the login page.
        */
        var self = this;
        this.search.on('keyup', function() {
            var test = $.get(self.typeaheadUrl);
            test.error(function(response) {
                if (response.status == '401') {
                    alert(self.timeoutMessage)
                    location.reload();
                }
            });
        });
    }
};

/**
 * UrlQuery.js
 *
 * Get URL query variables or URL parts
 *
 * Usage:
 * var query = Object.create(UrlQuery); // Create instance
 * With no arg, getUrlPart gets part. Negative numbers get parts in reverse order
 * var last = query.getUrlPart();
 * Get variable from query string (e.g., ?my_var=foo)
 * var myVar = query.getVar('my_var');
 *
 */
var UrlQuery = {
    getVar: function (varName) {
        var vars = this.getUrlVars();
        return (vars[varName]);
    },
    getUrlVars: function () {
        var vars = [], hash;
        var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
        for (var i = 0; i < hashes.length; i++) {
            hash = hashes[i].split('=');
            vars.push(hash[0]);
            vars[hash[0]] = hash[1];
        }
        return vars;
    },
    getUrlPart: function(index) {
        index = index || - 1;
        var parts = window.location.href.split('/');
        return (index < 0) ? parts.slice(index)[0] : (parts[index]);
    }
}


$(document).ready(function ($) {
    if ($('[name="_token"]').is('*')) {
        function refreshToken(){
            $.get(appSpace.baseUrl + '/refresh-csrf').done(function(data){
                // 'data' contains the new token. Add to appSpace
                appSpace['csrfToken'] = data;
                // Since there might be more than one form,
                // iterate over all and set value of the _token hidden input(s)
                $('[name="_token"]').each(function() {
                    $(this).val(appSpace.csrfToken);
                });
            });
        }

        setInterval(refreshToken, 3600000); // 1 hour
    }
});
// JS code for Member Edit page

$(document).ready(function ($) {

    if ($('#member_store').is('*') || $('#member_update').is('*')) {
        // Set date pickers
        $('.date-pick').datepicker({
            format: 'MM d, yyyy',
            orientation: 'bottom'
        });
        $('.date-pick-short').datepicker({
            format: 'mm/dd/yyyy',
            orientation: 'bottom'
        }).on('show', function(evt) {
            // Make sure date is set on picker when it first opens, but not after that
            if (evt.dates.length == 0) {
                $(this).datepicker('update');
            }
        });

         // Detect any changes to the form data
         $('#member_store, #member_update').dirtyForms()
             .on('dirty.dirtyforms clean.dirtyforms', function (ev) {
                 var $submitButton = $('#submit_update');
                 if (ev.type === 'dirty') {
                     $submitButton.removeAttr('disabled');
                 } else {
                     $submitButton.attr('disabled', 'disabled');
                 }
             });

        // Create/Save for Member form
        var memberSave = Object.create(AjaxPost);
        memberSave.init({
            formSelector: '#member_store, #member_update',
            setupAction: function(){
                $('#member_saving').removeClass('hidden');
                $('.saved').addClass('hidden');
            },
            cleanupAction: function(){
                $('#member_store, #member_update').dirtyForms('setClean');
                $('#submit_update').attr('disabled', 'disabled');
                $('#member_saving').addClass('hidden');
                $('input, select, textarea').removeClass('error');
            },
            newAction: function(data){
                document.location = appSpace.baseUrl + '/member/details/' + data.member_id;
            },
            successAction: function(){
                $('.saved').removeClass('hidden')
                    .show()
                    .fadeOut(3000);
            },
            errorAction: function(errors){
                $('#member_saving').addClass('hidden');
                var formErrors = Object.create(FormErrors);
                formErrors.show({
                    append: true,
                    messages: '#error_messages',
                    errors: errors
                });
            }
         });

        // CRUD for Contacts
        var contactsCRUD = Object.create(AjaxCRUD);
        contactsCRUD.init({
            formSelector: '#update_contact',
            addSelector: '#add_contact',
            editSelector: '#contacts',
            idSelector: '#contact_id',
            listSelector: '#contacts',
            modalSelector: '#contact_modal',
            saveSelector: '#contact_save',
            deleteSelector: '#contact_delete',
            submitSelector: '#contact_save',
            cancelSelector: '#contact_cancel',
            retrieveFormUrl: appSpace.baseUrl + '/contact/show',
            retrieveListUrl: appSpace.baseUrl + '/member/contacts',
            deleteDataUrl: appSpace.baseUrl + '/contact/delete',
        });

         // CRUD for Dues payments
        var duesCRUD = Object.create(AjaxCRUD);
        duesCRUD.init({
            formSelector: '#update_dues',
            addSelector: '#add_dues',
            editSelector: '#dues',
            idSelector: '#dues_id',
            listSelector: '#dues',
            modalSelector: '#dues_modal',
            saveSelector: '#dues_save',
            deleteSelector: '#dues_delete',
            submitSelector: '#dues_save',
            cancelSelector: '#dues_cancel',
            retrieveFormUrl: appSpace.baseUrl + '/dues/show',
            retrieveListUrl: appSpace.baseUrl + '/member/dues',
            deleteDataUrl: appSpace.baseUrl + '/dues/delete',
        });

        // CRUD for Board Roles
        var rolesCRUD = Object.create(AjaxCRUD);
        rolesCRUD.init({
            formSelector: '#update_role',
            addSelector: '#add_role',
            editSelector: '#board_roles',
            idSelector: '#roles_id',
            listSelector: '#board_roles',
            modalSelector: '#role_modal',
            saveSelector: '#role_save',
            deleteSelector: '#role_delete',
            submitSelector: '#role_save',
            cancelSelector: '#role_cancel',
            retrieveFormUrl: appSpace.baseUrl + '/role/show',
            retrieveListUrl: appSpace.baseUrl + '/member/roles',
            deleteDataUrl: appSpace.baseUrl + '/role/delete',
        });
    }
});

// JS code for Member List page

$(document).ready(function ($) {
    if ($('.member-list').is('*')) {
        $('.member-list tbody tr').on('click', function () {
            var id = $(this).attr('data-id');
            document.location = appSpace.baseUrl + '/member/details/' + id;
        });

        var mainMemberList = $('#main_member_list').DataTable({
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]], // Number of entries to show
            iDisplayLength: -1,
            aaSorting: [],
            fnDrawCallback: function() {
                // Hide pagination buttons of only one page is showing
                var $paginates = $('.dataTables_paginate').find('.paginate_button');
                $('.dataTables_paginate').toggle($paginates.length > 3);
            },
            initComplete: function () {
                var $search = $($(this).selector + '_filter').find('input[type="search"]');
                // Add 'clearable' x to search field, and callback to restore table on clear
                $search.addClass('clearable').clearable({
                    onClear: function() {
                        guildMemberList.search( '' ).columns().search( '' ).draw();
                    }
                });
                // Add filter dropdowns to dataTables.js header
                var addFilters = Object.create(AddColumnFilters);
                addFilters.init({ dataTables: this });
                // Retrieve coven names into select via AJAX
                var reviseSelect = Object.create(ReviseSelect);
                reviseSelect.init({
                    ajaxUrl: '/public/member/covens',
                    selector: '[aria-label^="Coven"]',
                    width: '75px',
                    isChild: true,
                    prepend: {value: '', text: 'Coven'},
                    useOriginalValues: true
                });
            }
        });
    }
});


// JS code for Member List page

$(document).ready(function ($) {
    if ($('.missing-list').is('*')) {
        $('.missing-list tbody tr').on('click', function () {
            var id = $(this).attr('data-id');
            document.location = appSpace.baseUrl + '/member/details/' + id;
        });
    }
});



/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example', require('./components/Example.vue'));

const app = new Vue({
    el: '#app'
});

//# sourceMappingURL=all.js.map
