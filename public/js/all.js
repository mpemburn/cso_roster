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

/* Specific to the guild manager page */

$(document).ready(function ($) {
    if ($('#guild_manage').is('*')) {
        var guild = appSpace.urlQuery.getUrlPart();
        var guildTable = Object.create(TableManager);
        guildTable.init({
            typeaheadUrl: appSpace.baseUrl + '/member/search?q=%QUERY&guild_id=' + guild,
            addUrl: appSpace.baseUrl + '/guild/add?guild_id=' + guild,
            removeUrl: appSpace.baseUrl + '/guild/remove?guild_id=' + guild,
            idName: 'member_id',
            tableSelector: '#guild_member_list',
            searchSelector: '#guild_search',
            addSelector: '#guild_add_member',
            removeSelector: '.guild-remove',
            canEdit: appSpace.canEdit,
            timeoutMessage: appSpace.authTimeout,
            onTableComplete: function() {
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

// JS code for Member Edit page

$(document).ready(function ($) {

    if ($('#member_update').is('*')) {
        $('.date-pick').datepicker({
            format: 'M d, yyyy',
            orientation: 'bottom'
        });

        $('[name=phone_button]').on('click', function() {
            var value = $(this).val();
            $('[name=Primary_Phone]').val(value);
        });

        /* Use FieldToggle to toggle visibility of date fields */
        var toggler = Object.create(FieldToggle);
        $('#member_degree').on('change, if_yes', function () {
            toggler.doToggle({
                toggleType: 'select_multi',
                actorSelector: '#member_degree',
                actionSelector: '.degree-date',
                multiAttribute: 'data-degree-date'
            });
        })

        $('#bonded_check').on('click if_yes', function () {
            toggler.doToggle({
                toggleType: 'checkbox',
                actorSelector: '#' + $(this).attr('id'),
                actionSelector: '.form-group.bonded-date'
            });
        });

        $('#solitary_check').on('click if_yes', function () {
            toggler.doToggle({
                toggleType: 'checkbox',
                actorSelector: '#' + $(this).attr('id'),
                actionSelector: '.form-group.solitary-date'
            });
        });

        $('#leadership-role').on('change if_yes', function () {
            toggler.doToggle({
                toggleType: 'select',
                actorSelector: '#' + $(this).attr('id'),
                actionSelector: '.form-group.leadership-date',
                emptyValue: '0'
            });
        });

        $('#board-role').on('change if_yes', function () {
            toggler.doToggle({
                toggleType: 'select',
                actorSelector: '#' + $(this).attr('id'),
                actionSelector: '.form-group.expiry-date',
                emptyValue: ''
            });
        });

        $('#leadership-role, #board-role').trigger('if_yes');

        /* Detect any changes to the form data */
        $('#member_update').dirtyForms()
            .on('dirty.dirtyforms clean.dirtyforms', function (ev) {
                var $submitButton = $('#submit_update');
                if (ev.type === 'dirty') {
                    $submitButton.removeAttr('disabled');
                } else {
                    $submitButton.attr('disabled', 'disabled');
                }
            });
        /* Submit form via AJAX */
        $('#member_update').on('submit', function (e) {
            var formAction = this.action;
            $.ajaxSetup({
                header: $('meta[name="_token"]').attr('content')
            });
            e.preventDefault(e);

            $('#member_saving').removeClass('hidden');
            $('.saved').addClass('hidden');

            $.ajax({
                type: "POST",
                url: formAction,
                data: $(this).serialize(),
                dataType: 'json',
                success: function (data) {
                    var response = data.response;
                    $('#member_update').dirtyForms('setClean');
                    $('#submit_update').attr('disabled', 'disabled');
                    $('#member_saving').addClass('hidden');
                    $('input, select, textarea').removeClass('error');
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
                        document.location = appSpace.baseUrl + '/member/details/' + response.member_id;
                    }
                    if (response.status) {
                        $('.saved').removeClass('hidden')
                            .show()
                            .fadeOut(3000);
                    }
                },
                error: function (response) {
                    console.log(response);
                    $('#member_update').dirtyForms('setClean');
                    // Warn user that the session has timed out, then reload to go to login page
                    if (response.status == '401') {
                        alert(appSpace.authTimeout)
                        location.reload();
                    }
                }
            })
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
