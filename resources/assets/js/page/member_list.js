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
                        mainMemberList.search( '' ).columns().search( '' ).draw();
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
spokenR0ad!
nobodyH0me~
