window.$ = window.jQuery = require('jquery');
require('jquery-validation');
import 'jquery-datetimepicker';

$(document).ready(function() {

  $("#start_date, #end_date").datetimepicker({
    format:  'Y-m-d',
    minDate: 0,
    timepicker: false,
  });

  $("#start_time, #end_time").datetimepicker({
    format:  'H:i',
    datepicker: false
  });

    $(".close-modal").click(function() {
        $(".modal").each(function(index,element){
            if(!$(element).hasClass('hidden')) {
                $(element).addClass('hidden')
            }
        });
    });

    $(document).on('click', '.edit_details_btn', function(event) {
        event.preventDefault();
        openEditPopup(this);
    });

    $(document).on('click', '.reject_details_btn', function(event) {
        event.preventDefault();
        $("#reject-modal form").attr('action', $(this).attr('data-url'));
        $("#reject-modal").removeClass('hidden');

    });

    $("#type").change(function() {
        if($(this).val('partial')) {
            $(".area").removeClass('hidden');
        } else {
            $(".area").addClass('hidden');
        }
    });

    validateForm('#booking_form');
    validateRejectionForm("#reject_booking_form");


    $("#search_keywords").keyup(function() {

        var url = base_url + '/warehousebookings';
        if(user_role.indexOf('admin') != -1) {
            url = base_url + '/admin/warehousebookings';
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

    $("#start_date, #start_time").change(function() {
      $("#start").val($("#start_date").val() + ' ' + $("#start_time").val());
    });

    $("#end_date, #end_time").change(function() {
      $("#end").val($("#end_date").val() + ' ' + $("#end_time").val());
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
        var statusClass = value.booking_status != "confirmed" ?  'text-green-500' : 'text-red-500';
        var isApprovedClass = value.is_approved ?  'text-green-500' : 'text-red-500';

        html += '<tr class="hover:bg-gray-100 border-b border-gray-200 py-10 search-results">';

        html += ' <td class="px-4 py-4">' + value.id + '</td>';
        html += ' <td class="px-4 py-4">' + value.warehouse.id + '</td>';
        html += ' <td class="px-4 py-4">' + value.booked_by.name + '</td>';
        html += ' <td class="px-4 py-4">' + value.item + '</td>';
        html += ' <td class="px-4 py-4">' + value.start_time + '</td>';
        html += ' <td class="px-4 py-4">' + value.end_time + '</td>';
        html += ' <td class="px-4 py-4">' + value.booking_type + '</td>';
        html += ' <td class="px-4 py-4 ' +statusClass+'">' + alue.booking_status + '</td>';
        html += ' <td class="px-4 py-4">' + value.created_at + '</td>';

        html += '</tr>';
    });


    $(displayDiv).html(html);
}

function populatePagination(paginationData) {
    $('#pagination').html(paginationData);
}

function openEditPopup(modalObj) {
    $.ajax({
        method: 'GET',
        url: $(modalObj).attr('data-url'),
        dataType: 'json',
        success: function(response) {
            var isPartiallyBooked = false;
            $.each(response,function (key,value) {
                if(key == "type") {
                    if(value == "partial") {
                        $("#booking_form .area").removeClass('hidden');
                    }
                } else if(key == "start_time") {
                    setDateTime(value,"start");
                    $("#start").val(value);
                } else if(key == "end_time") {
                    setDateTime(value, "end");
                    $("#end").val(value);
                } else {
                    $("#booking_form #" + key).val(value);
                }

            });
            if(isPartiallyBooked) {
                $("#booking_form .area").removeClass('hidden');
            }

        },
        error: function(xhr) {
            // location.reload();
        }
    })
    $("#booking_form").attr('action', $(modalObj).attr('data-url'));
    $("#edit_booking_modal").removeClass('hidden');

}

function setDateTime(dateTime, startOrEnd){
    dateTime = dateTime.split(' ');
    if(startOrEnd == "start") {
        $('#'+startOrEnd+'_date').val(dateTime[0]);
        $('#'+startOrEnd+'_time').val(dateTime[1]);
    } else {
        $('#'+startOrEnd+'_date').val(dateTime[0]);
        $('#'+startOrEnd+'_time').val(dateTime[1]);
    }


}

function validateForm(formObj) {

    $(formObj).validate({
        rules: {
            title: {
                required: true,
            },
            start_date: {
                required: true,
                dateISO: true,
            },
            start_time: {
                required: true,
            },
            end_date: {
                required: true,
                dateISO: true,
            },
            end_time: {
                required: true,
            },
            description: {
                required: true
            },
            quantity: {
                number: true
            },
            unit: {
                required: function(element) {
                    return $("#quantity").val().length > 0
                }
            },
            goods_value: {
                number: true
            },
            booking_status: {
                required: true
            },
        },
        messages: {
            title: {
                required: "Enter booking title",
            },
            start_date: {
                required: "Booking start date can not be empty",
                dateISO: "Enter date in the following format yyyy-mm-dd",
            },
            start_time: {
                required: "Booking start time is required",
            },
            end_date: {
                required: "Booking end date can not be empty",
                dateISO: "Enter date in the following format yyyy-mm-dd",
            },
            end_time: {
                required: "Booking end time is required",
            },
            description: {
                required: "Booking details are required"
            },
            quantity: {
                number: "Quantity can contain digits and decimal only"
            },
            unit: {
                required: "Please specify unit"
            },
            goods_value: {
                number: "Goods Value can contain digits and decimal only"
            },
            booking_status: {
                required: "Please select booking status"
            },
        },
        submitHandler: function(form) {
            // do other things for a valid form
            form.submit();
        }
    });
}

function validateRejectionForm(formObj){
    $(formObj).validate({
        rules: {
            reason: {
                required: true,
            }
        },
        messages: {
            reason: {
                required: "Enter reason for rejecting booking request",
            }
        },
        submitHandler: function(form) {
            // do other things for a valid form
            form.submit();
        }
    });
}
