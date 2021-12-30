window.$ = window.jQuery = require('jquery');

$(document).ready(function() {

    $("#search_keywords").keyup(function() {
        var url = base_url + '/admin/users';
        ajaxSearch(url, true, ".search_results");
    });

    $(document).on('click', ".edit_role", function (event) {
        var currentRole = $(this).attr('data-role');
        $("#role option:contains(" + currentRole + ")").attr('selected', 'selected');
        $("#edit_user_role_form").attr('action', $(this).attr('data-target-url'));
        $("#edit_role_modal").removeClass('hidden');
    });

    $(".close-modal").click(function() {
        $(".modal").each(function(index,element){

            if(!$(element).hasClass('hidden')) {
                $(element).addClass('hidden')
            }
        });
    });

});
