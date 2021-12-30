const { ajax } = window.$ = window.jQuery = require('jquery');
require('jquery-validation');
require('devbridge-autocomplete');
import 'jquery-datetimepicker';

var slideIndex = 3;


var scrollParticipantsFunction = function() {

    var el_scroll_top = $(this).scrollTop();
    var el_scroll_h = $(this).innerHeight();
    var page = $("#page").val();
    var totalParticpants = $("#total").val();
    var searchInput = $(this).val();
    if(el_scroll_top + el_scroll_h >= $(this)[0].scrollHeight && page < totalParticpants) {
        $('.scrollable').unbind('scroll');
        getConversations(page, searchInput, false);
    }
};


$(document).ready(function() {

  $("#send_msg_btn").click(function() {
        var activeConversationId = $('.participants.active').attr('data-conversation-id');
        var formData = new FormData();
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
        formData.append('message', $("#message").val());
        sendMessage(activeConversationId, formData);
    });


    $("#message_form").submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
        var activeConversationId = $('.participants.active').attr('data-conversation-id');
        sendMessage(activeConversationId, formData);
    });

    $(document).on('click', '.participants', function() {

        if(!$(this).hasClass('active')) {
            var conversationId = $(this).attr('data-conversation-id');
            getConversationMessages(conversationId);
            $('.participants').removeClass('active');
            $(this).addClass('active');
            getLabels(conversationId);
            getReminders(conversationId);
        }
    });



    $('.scrollable').scroll(scrollParticipantsFunction);

    $("#attachment_btn").click(function() {
        $("#attachment-modal").removeClass('hidden');
    });

    $(document).on('change', '#image_path, .photo', function() {
        readURL(this);
        $(this).parents('.img_div').find('.remove_image').removeClass('hidden');
    });

    $("#reminder_date_time").datetimepicker({
        format:'Y-m-d H:i'
    });

    $("#reminder").click(function() {
        $("#reminder-modal").removeClass('hidden');
    });

    $("#reminder_form").submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
        var activeConversationId = $('.participants.active').attr('data-conversation-id');
        setReminder(activeConversationId, formData);
    });

    $(".close-modal").click(function() {
        $(".modal").each(function(index,element){

            if(!$(element).hasClass('hidden')) {
                $(element).addClass('hidden')
            }
        });
    });

    $("#notes").click(function() {
        $("#notes-modal").removeClass('hidden');
    });

    $("#notes_form").submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
        var activeConversationId = $('.participants.active').attr('data-conversation-id');
        saveNotes(activeConversationId, formData);
    });

    $("#labels").click(function() {
        $("#label-modal").removeClass('hidden');
    });

    $("#labels_form").submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
        var activeConversationId = $('.participants.active').attr('data-conversation-id');
        saveLabels(activeConversationId, formData);
    });

    $(document).on("change", "#set_reminders .reminder", function() {
        if(this.checked) {
            var reminderId = $(this).attr('id').replace('reminder_','');
            markReminderAsDone(reminderId);
        }
    });

    $("#attach_catalog").click(function() {
        $("#catalog-modal").removeClass('hidden');
    });

    $("#catalog_form").submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
        var activeConversationId = $('.participants.active').attr('data-conversation-id');
        sendCatalog(activeConversationId, formData);
    });

    $(".error-btn-close").click(function() {
        $(this).parent('div').addClass('hidden');
    });

    $("#chat_search").keyup(function() {
        var searchInput = $(this).val();
        getConversations(1, searchInput, true);
    });

    // showSlides(slideIndex);

    $('.prev').click(function(){
        plusSlides(-1);
    });

    $('.next').click(function(){
        plusSlides(1);
    });

    $(document).on('click', '.remove_image' ,function() {
        var imgId = $(this).prev('img').attr('id');
        var fileInputId = imgId.replace('_preview','');
        $(`#${fileInputId}`).val('');
        $(`#${imgId}`).attr('src', base_url + '/img/camera_icon.png');
        $(this).addClass('hidden');
    });


});

function sendMessage(conversationId, formData) {

    $.ajax({
        method: 'POST',
        url: base_url + '/chat/send_message/' + conversationId,
        dataType: 'json',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function(response) {
            if('sent_message' in response) {
                $(response.sent_message).each(function(key,message) {
                    createMsgSent(message, 'justify-end');
                });

                $('.participants.active .last_message').text(response.last_message);
                $("#message").val('');
            }

            if(!$("#attachment-modal").hasClass('hidden')) {
                $("#attachment-modal").addClass('hidden');
            }

        },
        error: function(xhr) {
            if(xhr.status == 422) {
                var text = '';
                if(formData.has('attachment_path')) {

                    var validationErrors = xhr.responseJSON.data;

                    for (const [key, value] of Object.entries(validationErrors)) {
                        text = value;
                        break;
                    }

                } else {
                    text = 'Can not send empty message';
                }

                $("#error_message").text(text);
                $("#error_message").parent('div').removeClass('hidden');

                if(!$("#attachment-modal").hasClass('hidden')) {
                    $("#attachment-modal").addClass('hidden');
                }
            }
        }
    });
}

function getConversationMessages(conversationId) {
    $.ajax({
        method: 'GET',
        url: base_url + '/chat/get_messages/' + conversationId,
        dataType: 'json',
        success: function(response) {
            $('.messages').html('');

            $(response).each(function(key,message) {
                var isSender = '';
                if(message.is_sender == 0) {
                    if(message.type == "reminder" || message.type == "notes") {
                        return;
                    } else {
                        isSender = 'justify-start';
                    }
                } else {
                    if(message.type == "reminder" || message.type == "notes") {
                        isSender = 'justify-center';
                    } else {
                        isSender = 'justify-end';
                    }
                }
                var html =  '<div class="flex mb-4 ' + isSender + '">';
                    html += '<div class="mr-2 py-3 px-4 bg-blue-400 rounded-bl-3xl rounded-tl-3xl rounded-tr-xl text-white max-w-md break-words">';
                        if (message.type == "document") {
                            html += '<div class="bg-white p-2"><a href="' + message.body + '" target="_blank"><img class="attachment" src="' + base_url + '/img/docs.png' +  '"/></a></div>';
                        } else if(message.type == "image") {
                            html += '<div class="bg-white p-2"><a href="' + message.body +'" target="_blank"><img class="attachment" src="' + message.body + '"/></a></div>';
                        } else if (message.type == "reminder") {
                            html+= '<div class="p-2"><span><i class="fa fa-clock"></i> | Call Reminder Set For: '  + message.body + '</span></div>';
                        } else if (message.type == "notes") {
                            html += '<div class="p-2"><span><i class="fa fa-sticky-note"></i>| ' + message.body + '</span></div>';
                        } else {
                            html += message.body;
                        }



                    html += '</div>';
                html += '</div>';
                $('.messages').append(html);
                $('.unread_count').text('( ' + 0 + ' )');
                $("html, body").animate({ scrollBottom: $(".messages").scrollTop() }, 1000);
           });
        }


    });
}


function getConversations(page, searchInput, refreshContacts) {

    if(refreshContacts) {
        page = 1;
    } else {
        page = Number(page) + 1;
    }


    $.ajax({
        method: 'GET',
        url: window.location.href + '?search=' + searchInput + '&page=' + page,
        dataType: 'json',
        success: function(response) {
            if(response) {

                if(refreshContacts) {
                    $('.scrollable').html('');
                }

                var index = 0;
                var firstConversationId = '';
                var companyName = '';
                var websiteUrl = '';
                var phoneNumber = '';
                var contactPerson = '';

                $(response).each(function(key,participants) {
                    var conversationId = (Object.keys(participants))[0];

                    var html =  '<div class="flex flex-row py-4 px-2 items-center border-b-2 border-l-4 participants" data-conversation-id="' + conversationId + '">';
                        html += '<div class="w-1/4">';
                            if(participants[conversationId].participant.company_logo != null) {
                                html += '<img src="' + participants[conversationId].participant.company_logo + '" class="object-cover h-12 w-12 rounded-full" alt="" />';
                            } else {
                                html += '<img src="https://source.unsplash.com/L2cxSuKWbpo/600x600" class="object-cover h-12 w-12 rounded-full" alt="" />';
                            }
                        html +=  '</div>';
                                html += '<div class="w-full">';
                                    html += '<div class="text-lg font-semibold">' + participants[conversationId].participant.name + '</div>';
                                    html += '<span class="text-gray-500 last_message">' + participants[conversationId].last_message.body + '</span>';
                                html += '</div>';
                    html += '</div>';

                    $('.scrollable').append(html);

                    if(index == 0) {
                        firstConversationId = conversationId;
                        companyName = participants[conversationId].participant.business != null ? participants[conversationId].participant.business.company_name : 'NA';
                        websiteUrl = participants[conversationId].participant.business != null
                                        && participants[conversationId].participant.business.alternate_website != null
                                    ? participants[conversationId].participant.business.alternate_website : 'NA';
                        phoneNumber = participants[conversationId].participant.business != null ? participants[conversationId].participant.business.phone_number : participants[conversationId].participant.phone;
                        contactPerson = participants[conversationId].participant.name;
                    }

                    index++;
                });

                if(refreshContacts && response.length > 0) {
                    $(".participants[data-conversation-id='" + firstConversationId + "']").trigger('click');
                    $("#company_name").text(companyName);
                    $("#website_url").text(websiteUrl);
                    $("#phone_number").html('<i class="fad fa-phone"></i> ' + phoneNumber);
                    $("#contact_person").html('<i class="fad fa-user"></i> ' + contactPerson);
                }

                $("#page").val(page);
                $('.scrollable').bind('scroll', scrollParticipantsFunction);
            }
        }
    });
}

function createMsgSent(message, justifyClass) {
    var html =  '<div class="flex ' + justifyClass + ' mb-4">';
        html += '<div class="mr-2 py-3 px-4 bg-blue-400 rounded-bl-3xl rounded-tl-3xl rounded-tr-xl text-white max-w-md break-words">';
            html += message;
        html += '</div>';
    html += '</div>';
    $('.messages').append(html);
}

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        var inputId = $(input).attr('id');

        if(inputId.indexOf('q_image') != -1) {
            var fieldNo = inputId.replace('q_image_', '');
            $(`#q_image_path_${fieldNo}`).val('');
        }

        reader.onload = function(e) {
            if(input.files[0].type.indexOf('image') != -1) {
                $('#' + inputId + '_preview').attr('src', e.target.result);
            } else {
                $('#' + inputId + '_preview').attr('src', base_url + '/img/docs.png');
            }

            if(!$('#' + inputId + '_preview').hasClass('h-40')) {
                $('#' + inputId + '_preview').addClass('h-40');
            }

        }


      reader.readAsDataURL(input.files[0]); // convert to base64 string
    }
}

function setReminder(conversationId, formData) {

    $.ajax({
        method: 'POST',
        url: base_url + '/chat/set_reminder/' + conversationId,
        dataType: 'json',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function(response) {

            createMsgSent(response.sent_message, 'justify-center');

            $("#reminder-modal").addClass('hidden');
            $("#reminder_date_time").val('');
        },
        error: function(xhr) {
            showError(xhr, "#reminder-modal");
        }
    });
}


function saveLabels(conversationId, formData) {

    $.ajax({
        method: 'POST',
        url: base_url + '/chat/save_labels/' + conversationId,
        dataType: 'json',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function(response) {
            // console.log(response);
            // $(response).each(function(key,labelId) {
            //     $("#label_"+labelId).attr('checked', 'checked');
            // });
            $("#label-modal").addClass('hidden');
        }
    });
}

function saveNotes(conversationId, formData) {

  $.ajax({
      method: 'POST',
      url: base_url + '/chat/save_notes/' + conversationId,
      dataType: 'json',
      data: formData,
      cache: false,
      contentType: false,
      processData: false,
      success: function(response) {
          $("#notes-modal").addClass('hidden');
      }
  });
}

function getLabels(conversationId) {

    $.ajax({
        method: 'GET',
        url: base_url + '/chat/get_labels/' + conversationId,
        dataType: 'json',
        success: function(response) {
            $("#labels_form input[type=checkbox]").removeAttr('checked');
            $(response).each(function(key,labelId) {
                $("#label_"+labelId).attr('checked', 'checked');
            });
        }
    });
}

function getReminders(conversationId) {

    $.ajax({
        method: 'GET',
        url: base_url + '/chat/get_reminders/' + conversationId,
        dataType: 'json',
        success: function(response) {
            $("#set_reminders").html("");
            if( response.reminders.length > 0 ) {
                $(response.reminders).each(function(key,reminder) {
                    var isInPastClass = new Date().getTime() > new Date(reminder.reminder_date_time).getTime() ? 'text-red-500': 'text-green-500';
                    var html = '';
                    html = '<div class="w-1/2 md:w-full px-3 mb-6" id="reminder_div_' + reminder.id + '"></div>';
                        html += '<label class="uppercase tracking-wide ' + isInPastClass + ' text-xs font-bold mb-2" for="reminders">'
                            html += reminder.reminder_date_time;
                        html += '</label>';
                        html += '<span class="text-gray-700 text-xs font-bold">Mark as done</span>';
                        html += '<input class="bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500 reminder" name="reminder[' + reminder.id + ']" id="reminder_' + reminder.id + '" type="checkbox">';
                    html += '</div>';
                    $("#set_reminders").append(html)
                });
            }
        }
    });
}

function markReminderAsDone(reminderId) {

    $.ajax({
        method: 'POST',
        url: base_url + '/chat/reminder_done/' + reminderId,
        dataType: 'json',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if(response == 1) {
                $("#reminder_div_"+reminderId).remove();
            }
        }
    });
}

function sendCatalog(conversationId, formData) {

    $.ajax({
        method: 'POST',
        url: base_url + '/chat/send_catalog/' + conversationId,
        dataType: 'json',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function(response) {
            $(response.sent_message).each(function(key,message) {
                createMsgSent(message, 'justify-end');
            });

            $('.participants.active .last_message').html(response.sent_message);
            $("catalog_form #message").val('');
        },
        error: function(xhr) {
            showError(xhr, '#catalog-modal');
        }
    });
}

function plusSlides(n) {
    showSlides(slideIndex += n);
}

function currentSlide(n) {
    showSlides(slideIndex = n);
}

function showSlides(n) {
    var i;
    var slides = document.getElementsByClassName("quick_replies");

    if (n > slides.length) {
        slideIndex = 3;
    }

    if (n < 1) {
        slideIndex = slides.length - 2;
    }

    if(n == slides.length) {
        $(".next").addClass('hidden');
    }

    if(n == slides.length - 3) {
        $(".prev").addClass('hidden');
    }

    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }

    for(var key = n - 3; key < n; key++) {
        slides[key].style.display = "block";
    }


}

function showError(xhr, modalId) {
    if(xhr.status == 422) {
        var text = '';

        var validationErrors = xhr.responseJSON.data;

        for (const [key, value] of Object.entries(validationErrors)) {
            text = value;
            break;
        }


        $("#error_message").text(text);
        $("#error_message").parent('div').removeClass('hidden');

        if(!$(modalId).hasClass('hidden')) {
            $(modalId).addClass('hidden');
        }
    }
}
