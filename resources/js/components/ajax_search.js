$('body').on('click', '#pagination a', function(e) {
    e.preventDefault();

    $('#load a').css('color', '#dfecf6');
    $('#load').append('<img style="position: absolute; left: 0; top: 0; z-index: 100000;" src="/images/loading.gif" />');

    var url = $(this).attr('href');
    ajaxSearch(url, false, ".search_results");
    window.history.pushState("", "", url);
});

function isAdmin() {
    var pathName = window.location.pathname;
    if(pathName.indexOf('/admin') != -1) {
        return true;
    } else {
        return false;
    }
}

function ajaxSearch(ajaxUrl, addKeyword, displayDiv) {

    if(addKeyword) {
        var searchKeyword = $("#search_keywords").val();
        ajaxUrl = ajaxUrl + '?keywords=' + searchKeyword;
    }

    $.ajax({
        method: 'GET',
        url: ajaxUrl,
        dataType: 'html',
        success: function(response) {
            populateTable(response, displayDiv);
            populatePagination(response.paginator);
        },
        error: function(xhr) {

        }
    });
}
