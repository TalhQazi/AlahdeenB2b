window.$ = window.jQuery = require('jquery');
require('jquery-validation');

import 'jquery-ui/ui/widgets/datepicker';
import 'timepicker';

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

    $("#type").change(function() {
        if($(this).val() == 'partial') {
            $(".area").removeClass('hidden');
        } else {
            $(".area").addClass('hidden');
        }
    });

    validateForm('#booking_agreement_form');

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
            price: {
                required: true,
                number: true,
            },
            user_terms: {
                required: true
            },
            owner_terms: {
                required: true
            },

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
            price: {
                required: "Price is required",
                number: "Price can contain digits and decimal only",
            },
            user_terms: {
                required: "Enter terms and conditions for user"
            },
            owner_terms: {
                required: "Enter terms and conditions for warehouse owner"
            },
        },
        submitHandler: function(form) {
            // do other things for a valid form
            form.submit();
        }
    });
}


