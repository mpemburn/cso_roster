// JS code for Member List page

$(document).ready(function ($) {
    if ($('.missing-list').is('*')) {
        $('.missing-list tbody tr').on('click', function () {
            var id = $(this).attr('data-id');
            document.location = appSpace.baseUrl + '/member/details/' + id;
        });
    }
});

