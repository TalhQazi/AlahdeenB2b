window.$ = window.jQuery = require('jquery');
require('jquery-validation');

$(document).ready(function() {

    $(".close-modal").click(function() {
        $(".modal").each(function(index,element){
            if(!$(element).hasClass('hidden')) {
                $(element).addClass('hidden')
            }
        });
    });


    $(document).on('click', '.delete_warehouse_btn, .restore_warehouse_btn', function(event) {
        event.preventDefault();
        if($(this).hasClass('delete_warehouse_btn')) {
            openDeletePopup($(this).attr('data-url'), 'DELETE', 'Are you sure you want to delete warehouse?');
        } else {
            var msg = 'Are you sure you want to restore warehouse?';
            openDeletePopup($(this).attr('data-url'), 'PATCH', msg);
        }

    });

    $(document).on('click', '.approve_warehouse_btn, .disapprove_warehouse_btn', function(event) {
        event.preventDefault();

        var msg = 'Are you sure you want to approve warehouse?';
        if($(this).hasClass('disapprove_warehouse_btn')) {
            msg = 'Are you sure you want to disapprove warehouse?';
        }

        openDeletePopup($(this).attr('data-url'), 'PATCH', msg);

    });

    $(document).on('click', '.deactivate_warehouse_btn, .activate_warehouse_btn', function(event) {
        event.preventDefault();
        if($(this).hasClass('deactivate_warehouse_btn')) {
            openDeletePopup($(this).attr('data-url'), 'PATCH', 'Are you sure you want to deactivate warehouse, users would not be able to book it?');
        } else {
            var msg = 'Are you sure you want to reactivate warehouse?';
            openDeletePopup($(this).attr('data-url'), 'PATCH', msg);
        }

    });

    $("#search_keywords").keyup(function() {

        var url = base_url + '/warehouse';
        if(user_role.indexOf('admin') != -1) {
            url = base_url + '/admin/warehouse';
        }
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


function openDeletePopup(url, method, msg) {
    if(method == "DELETE") {
        $(".confirmation_msg_div").text(msg);
    } else {
        $(".confirmation_msg_div").text(msg);
    }

    $("#delete-modal form").attr('action',url);
    $("#delete-modal input[name='_method']").val(method);
    $("#delete-modal").removeClass('hidden');
}


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
            populateTable(response, displayDiv);
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
    $(responseData.warehouses.data).each(function(key,value) {

        var canBeShared = value.canBeShared == 1 ? 'Yes' : 'No';
        var isActive = value.is_active ? 'Yes' : 'No';
        var isApproved = value.is_approved ? 'Yes' : 'No';
        var isActiveClass = value.is_active ?  'text-green-500' : 'text-red-500';
        var isApprovedClass = value.is_approved ?  'text-green-500' : 'text-red-500';

        html += '<tr class="hover:bg-gray-100 border-b border-gray-200 py-10 search-results">';

        html += ' <td class="px-4 py-4">' + value.id + '</td>';
        html += ' <td class="px-4 py-4">' + value.locality + '</td>';
        html += ' <td class="px-4 py-4">' + value.city + '</td>';
        html += ' <td class="px-4 py-4">' + value.area + '</td>';
        html += ' <td class="px-4 py-4">' + value.price + '</td>';
        html += ' <td class="px-4 py-4">' + value.property_type.title + '</td>';
        html += ' <td class="px-4 py-4">' + canBeShared + '</td>';
        html += ' <td class="px-4 py-4 ' +isActiveClass+'">' + isActive + '</td>';


        if(user_role.indexOf('admin') != -1) {
            html += ' <td class="px-4 py-4 ' +isApprovedClass+'">' + isApproved + '</td>';
            html += ' <td class="px-4 py-4">' + value.created_at + '</td>';
            html += '<td class="px-4 py-4">';
            if(value.deleted_at) {
                html += '<a href="#" data-url="' + base_url + '/admin/warehouse/'+ value.id + '" title="Restore warehouse" class="ml-1 restore_warehouse_btn">'+
                            '<i class="fa fa-trash-restore mx-0.5"></i>'+
                        '</a>';
            } else {
                html += '<a href="' + base_url + '/admin/warehouse/'+ value.id + '" title="Edit Warehouse" class="mx-0.5 edit_warehouse_btn">'+
                            '<i class="fa fa-pencil mx-0.5"></i>'+
                        '</a>';
                if(value.is_approved == 0) {
                    html += '<a href="#" data-url="' + base_url + '/admin/warehouse/'+ value.id + '/approve' + '" title="Approve Warehouse" class="mx-0.5 approve_warehouse_btn">'+
                            '<i class="fa fa-toggle-off mx-0.5"></i>'+
                        '</a>';
                } else {
                    html += '<a href="#" data-url="' + base_url + '/admin/warehouse/'+ value.id + '/disapprove' + '" title="Dispprove Warehouse" class="mx-0.5 disapprove_warehouse_btn">'+
                            '<i class="fa fa-toggle-on mx-0.5"></i>'+
                        '</a>';
                }

                html += '<a href="#" data-url="' + base_url + '/admin/warehouse/'+ value.id + '" title="Delete warehouse" class="ml-1 delete_warehouse_btn">'+
                            '<i class="fa fa-trash mx-0.5"></i>'+
                        '</a>';
            }
            html += '</td>';
        } else {

            html += ' <td class="px-4 py-4">' + value.created_at + '</td>';

            html += '<td class="px-4 py-4">';
            html += '<a href="' + base_url + '/warehouse/'+ value.id + '" title="Edit Warehouse" class="mx-0.5 edit_warehouse_btn">'+
                        '<i class="fa fa-pencil mx-0.5"></i>'+
                    '</a>';
            html += '<a href="' + base_url + '/warehouse/'+ value.id + '/schedule' + '" title="View Schedule" class="mx-0.5">'+
                        '<i class="fa fa-eye mx-0.5"></i>'+
                    '</a>';
            if(value.is_active == 1) {
                html += '<a href="#" data-url="' + base_url + '/warehouse/'+ value.id + '/deactivate' + '" title="Deactivate warehouse" class="ml-1 deactivate_warehouse_btn">'+
                            '<i class="fa fa-toggle-on"></i>'+
                        '</a>';
            } else {
                html += '<a href="#" data-url="' + base_url + '/warehouse/'+ value.id + '/activate' + '" title="Activate warehouse" class="ml-1 activate_warehouse_btn">'+
                            '<i class="fa fa-toggle-off"></i>'+
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

