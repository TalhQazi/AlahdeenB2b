window.$ = window.jQuery = require('jquery');
require('jquery-validation');
require('timepicker');

import 'jquery-ui/ui/widgets/datepicker';


var startDatePicker = $("#start_date").datepicker({
    dateFormat:  'yy-mm-dd'
});

var endDatePicker = $("#end_date").datepicker({
    dateFormat:  'yy-mm-dd'
});

var startTimePicker = $("#start_time").timepicker({
    timeFormat: 'H:i:s',
    minTime: '00:00:00',
    maxTime: '23:59:59',
});

var endTimePicker = $("#end_time").timepicker({
    timeFormat: 'H:i:s',
    minTime: '00:00:00',
    maxTime: '23:59:59',
});

$(document).ready(function() {

    // validateForm('#booking_form');

    $(".close-modal").click(function() {
        $(".modal").each(function(index,element){
            if(!$(element).hasClass('hidden')) {
                $(element).addClass('hidden')
            }
        });
    });

    $(document).on('click', '.delete_booking', function(e) {
        e.preventDefault();
        var url = base_url + '/warehouse/' + $(this).attr('data-warehouse-id') + '/delete/' + $(this).attr('data-booking-id')
        showDeleteModal(url);
    });

    $(document).on('click', '.edit_details_btn', function(event) {
        event.preventDefault();
        openEditPopup(this);
    });

});


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
            }
        },
        messages: {
            title: {
                required: "Enter booking title",
            },
            start_date: {
                required: "Booked start date can not be empty",
                dateISO: "Enter date in the following format yyyy-mm-dd",
            },
            start_time: {
                required: "Booking start time is required",
            },
            end_date: {
                required: true,
                dateISO: "Enter date in the following format yyyy-mm-dd",
            },
            end_time: {
                required: "Booking end time is required",
            },
            description: {
                required: "Booking details are required"
            }
        },
        submitHandler: function(form) {
            // do other things for a valid form
            form.submit();
        }
    });

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
                    $("#booking_form #" + key).val(value);
                    if(value == "partial") {
                        $("#booking_form .area").removeClass('hidden');
                    }
                } else if(key == "start_time") {
                    var dateTime = new Date(value);
                    setDateTime(dateTime,"start");
                } else if(key == "end_time") {
                    var dateTime = new Date(value);
                    setDateTime(dateTime, "end");
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

function showDeleteModal(url) {
    $("#delete-modal form").attr('action', url);
    $("#delete-modal").removeClass('hidden');
}

function setDateTime(dateTime, startOrEnd){
    if(startOrEnd == "start") {
        var time = ('0' + dateTime.getHours()).substr(-2) + ":" + ('0' + dateTime.getMinutes()).substr(-2) + ":" + ('0' + dateTime.getSeconds()).substr(-2);
        var date = dateTime.getFullYear() + '-' + ('0' + (dateTime.getMonth() + 1)).substr(-2) + '-' + ('0' + dateTime.getDate()).substr(-2);
        startDatePicker.datepicker('setDate', date);
        startTimePicker.timepicker('setTime', time);
    } else {
        var time = ('0' + dateTime.getHours()).substr(-2) + ":" + ('0' + dateTime.getMinutes()).substr(-2) + ":" + ('0' + dateTime.getSeconds()).substr(-2);
        var date = dateTime.getFullYear() + '-' + ('0' + (dateTime.getMonth() + 1)).substr(-2) + '-' + ('0' + dateTime.getDate()).substr(-2);
        endDatePicker.datepicker('setDate', date);
        endTimePicker.timepicker('setTime', time);
    }
}
