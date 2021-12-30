window.$ = window.jQuery = require('jquery');

$(document).ready(function() {

    $("#search_keywords").keyup(function() {

        var url = base_url + '/warehousebookings/invoices';
        ajaxSearch(url, true, ".search_results");
    });

    $('body').on('click', '#pagination a', function(e) {
        e.preventDefault();

        $('#load a').css('color', '#dfecf6');
        $('#load').append('<img style="position: absolute; left: 0; top: 0; z-index: 100000;" src="/images/loading.gif" />');

        var url = $(this).attr('href');
        ajaxSearch(url, false, ".search_results");
        window.history.pushState("", "", url);
    });
});

function ajaxSearch(ajaxUrl, addKeyword, displayDiv) {

    if(addKeyword) {
        var searchKeyword = $("#search_keywords").val();
        ajaxUrl = ajaxUrl + '?keywords=' + searchKeyword;
    }

    $.ajax({
        method: 'GET',
        url: ajaxUrl,
        dataType: 'json',
        success: function(response) {
            populateTable(response, displayDiv)
            populatePagination(response.paginator);
        },
        error: function(xhr) {
            // location.reload();
        }
    });
}

function populateTable(responseData, displayDiv) {
    $('.search-results').html('');
    var html = '';
    $(responseData.invoices.data).each(function(key,value) {

        var isDeleted = value.deleted_at ? 'Deleted' : 'On';
        var isDeletedClass = value.deleted_at ? 'text-red-500' : 'text-green-500';
        var isOwner = value.warehouse_booking.booked_by.id == responseData.user_id ? false : true;

        html += '<tr class="hover:bg-gray-100 border-b border-gray-200 py-10 search-results">';

        html += ' <td class="px-4 py-4">' + value.id + '</td>';
        html += ' <td class="px-4 py-4">' + value.warehouse_booking.warehouse.id + '</td>';
        html += ' <td class="px-4 py-4">' + value.item + '</td>';
        html += ' <td class="px-4 py-4">' + value.start_time + '</td>';
        html += ' <td class="px-4 py-4">' + value.end_time + '</td>';
        html += ' <td class="px-4 py-4">' + value.booking_type + '</td>';
        html += ' <td class="px-4 py-4">' + value.booking_status + '</td>';
        html += ' <td class="px-4 py-4 ' +isDeletedClass+'">' + isDeleted + '</td>';
        html += ' <td class="px-4 py-4">' + value.created_at + '</td>';

        if(isDeleted == "On") {
            html += '<td class="px-4 py-4">';
            if(value.booking_status == "pending") {
                if(!isOwner) {
                    html += '<a target="_blank" href="' + base_url + '/warehousebookings/invoice/payment/'+ value.id + '" title="Make Payment" class="mx-0.5">'+
                        '<i class="fa fa-shopping-cart mx-0.5"></i>'+
                    '</a>';
                }
            } else {
                html += '<a target="_blank" href="' + base_url + '/warehousebookings/invoice/'+ value.id + '" title="View Booking Invoice" class="mx-0.5">'+
                        '<i class="fa fa-receipt mx-0.5"></i>'+
                    '</a>';
            }
            html += '</td>';
        }

        html += '</tr>';
    });


    $(displayDiv).html(html);
}

function populatePagination(paginationData) {
    $('#pagination').html(paginationData);
}

