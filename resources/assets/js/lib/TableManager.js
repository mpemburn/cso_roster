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
