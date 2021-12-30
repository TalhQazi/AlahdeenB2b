window.$ = window.jQuery = require('jquery');
require('jquery-validation');
require('timepicker');

import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction';

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

    var calendarEl = document.getElementById('calendar');
    let calendar = new Calendar(calendarEl, {
        selectable: true,
        plugins: [ dayGridPlugin, interactionPlugin ],
        initialView: 'dayGridMonth',
        headerToolbar: {
          left: 'prev,next today',
          center: 'title',
          right: 'dayGridMonth'
        },
        showNonCurrentDates: false,
        dayMaxEventRows: true,
        moreLinkClick: 'popover',
        eventSources: [
            {
                url: base_url + '/warehouse/' + warehouse_id + '/get_month_bookings',
            }
        ],
        eventClick: function(info) {
            if($(info.jsEvent.target).hasClass('fa-trash')) {
                showDeleteModal(info);
            } else {
                if(info.event.extendedProps.can_be_edited) {
                    showEditModal(info);
                }
            }

        },
        eventContent: function(arg) {
            let italicEl = document.createElement('span')
            let x = document.createElement('i');
            x.innerHTML = '<i class="fa fa-trash delete_booking ml-10"></i>';

            if(arg.event.extendedProps.can_be_deleted) {
                italicEl.innerHTML = arg.event.extendedProps.item + x.innerHTML;
            } else {
                italicEl.innerHTML = arg.event.extendedProps.item;
            }


            let arrayOfDomNodes = [ italicEl ]
            return { domNodes: arrayOfDomNodes }
        },
        selectOverlap: false,
        select: function(selectionInfo) {
            console.log(selectionInfo);


            startDatePicker.datepicker('setDate', selectionInfo.startStr);

            var startDateTime = new Date(selectionInfo.start);
            var time = ('0' + startDateTime.getHours()).substr(-2) + ":" + ('0' + startDateTime.getMinutes()).substr(-2) + ":" + ('0' + startDateTime.getSeconds()).substr(-2);
            startTimePicker.timepicker('setTime', time);

            // var endDate = new Date(selectionInfo.end);
            // var dateOffSet = (24*60*60*1000);
            // endDate = endDate.setTime(endDate.getTime() - dateOffSet);
            // endDate = new Date(endDate);
            // endDatePicker.datepicker('setDate', endDate.getFullYear() + '-' + endDate.getMonth() + '-' + endDate.getDate());

            endDatePicker.datepicker('setDate', selectionInfo.endStr);

            var endDateTime = new Date(selectionInfo.end);
            var time = ('0' + endDateTime.getHours()).substr(-2) + ":" + ('0' + endDateTime.getMinutes()).substr(-2) + ":" + ('0' + endDateTime.getSeconds()).substr(-2);
            endTimePicker.timepicker('setTime', time);

            $("#start").val($("#start_date").val() + ' ' + $("#start_time").val());
            $("#end").val($("#end_date").val() + ' ' + $("#end_time").val());

            $("#add_edit_booking_modal").removeClass('hidden');
        },
    });

    calendar.render();

    validateForm('#booking_form');

    $(".close-modal").click(function() {
        $(".modal").each(function(index,element){
            if(!$(element).hasClass('hidden')) {
                $(element).addClass('hidden')
            }
        });
    });

    $('document').on('click', '.delete_booking', function(e) {
        e.preventDefault();
        alert();
    });

    $("#start_time, #start_date").change(function() {
        $("#start").val($("#start_date").val() + ' ' + $("#start_time").val());
    });

    $("#end_date, #end_time").change(function() {
        $("#end").val($("#end_date").val() + ' ' + $("#end_time").val());
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

function showEditModal(info) {
    $("#title").val(info.event.title);
    $("#description").val(info.event.title);


    var dateTime = new Date(info.event.start);
    var time = ('0' + dateTime.getHours()).substr(-2) + ":" + ('0' + dateTime.getMinutes()).substr(-2) + ":" + ('0' + dateTime.getSeconds()).substr(-2);
    var date = dateTime.getFullYear() + '-' + ('0' + (dateTime.getMonth() + 1)).substr(-2) + '-' + ('0' + dateTime.getDate()).substr(-2);
    startDatePicker.datepicker('setDate', date);
    startTimePicker.timepicker('setTime', time);

    dateTime = new Date(info.event.end);
    time = ('0' + dateTime.getHours()).substr(-2) + ":" + ('0' + dateTime.getMinutes()).substr(-2) + ":" + ('0' + dateTime.getSeconds()).substr(-2);
    date = dateTime.getFullYear() + '-' + ('0' + (dateTime.getMonth() + 1)).substr(-2) + '-' + ('0' + dateTime.getDate()).substr(-2);
    endDatePicker.datepicker('setDate', date);
    endTimePicker.timepicker('setTime', time);

    $("#add_edit_booking_modal form").attr('action', base_url + '/warehousebookings/' + info.event.id);
    $("#add_edit_booking_modal").removeClass('hidden');

}

function showDeleteModal(info) {
    $("#delete-modal form").attr('action', base_url + '/warehouse/7/delete/' + info.event.id);
    $("#delete-modal").removeClass('hidden');
}

