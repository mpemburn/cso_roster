
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