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