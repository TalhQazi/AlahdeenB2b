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

    $(document).on('click', '#reject-btn', function(event) {
        event.preventDefault();
        $("#reject-modal form").attr('action', $(this).attr('data-url'));
        $("#reject-modal").removeClass('hidden');

    });

    validateRejectionForm("#reject_booking_form");

});


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
