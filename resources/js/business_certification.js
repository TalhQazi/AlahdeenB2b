window.$ = window.jQuery = require('jquery');
require('jquery-validation');
require('devbridge-autocomplete');
import intlTelInput from "intl-tel-input";

$(document).ready(function() {

    $(".photo").change(function() {
        readURL(this);
    });

    $("#add_certification_btn").click(function() {
        $("#business-certification-form input").each(function(key, element){
            if($(element).attr('id') != "business_id" && $(element).attr('name') != "_token") {
                $(element).val('');
            }
        });
        $("#business-certification-modal").removeClass("hidden");
    });

    $("#add_award_btn").click(function() {
        $("#business-award-form input").each(function(key, element) {
            if($(element).attr('id') != "award_business_id"  && $(element).attr('name') != "_token") {
                $(element).val('');
            }
        });
        $("#business-award-modal").removeClass("hidden");
    });

    $(document).on('click', '.edit_certification_btn, .edit_award_btn', function(event) {
        event.preventDefault();

        var openModal = '#business-certification-modal';
        if($(this).hasClass('edit_award_btn')) {
            openModal = '#business-award-modal';
        }

        openEditPopup($(this).attr('data-url'), openModal);
    });

    $(document).on('click', '.delete_certificate_award_btn', function(event) {
        event.preventDefault();
        openDeletePopup($(this).attr('data-url'));
    });

    $(".close-modal").click(function() {
        $(".modal").each(function(index,element){
            if(!$(element).hasClass('hidden')) {
                $(element).addClass('hidden')
            }
        });
    });

    validateCertificationForm('#business-contact-form');
    validateAwardForm('#business-award-form');

});


function validateCertificationForm(formObj) {

    $(formObj).validate({
        rules: {
            certification: {
                required: true,
                minlength: 3
            },
        },
        messages: {
            certification: {
                required: "Please enter standard certification",
                minlength: "Please enter at least 3 characters.",
            },
        },
        submitHandler: function(form) {
            // do other things for a valid form
            form.submit();
        }
    });
}

function validateAwardForm(formObj) {

    $(formObj).validate({
        rules: {
            title: {
                required: true,
                minlength: 3
            },
        },
        messages: {
            title: {
                required: "Please enter award title",
                minlength: "Please enter at least 3 characters.",
            },
        },
        submitHandler: function(form) {
            // do other things for a valid form
            form.submit();
        }
    });
}


function openEditPopup(url,openModal) {
    $.ajax({
        method: 'GET',
        url: url,
        dataType: 'json',
        success: function(response) {
            if(response.data != "") {
                $.each(response.data, function(index,value) {
                    if(index == "id") {
                        if(openModal == '#business-award-modal') {
                            $("#award_id").val(value);
                        } else {
                            $("#certification_id").val(value);
                        }
                    } else if(index == "image_path" || index == "award_image") {
                        if(value != "" && value != null) {
                            var image_path = value.replace('public','storage');
                            image_path = base_url + '/' + image_path;
                            if(openModal == '#business-award-modal') {
                                $("#award_image_preview").attr('src',image_path);
                                $("#award_image_path_preview").addClass('h-40');
                            } else {
                                $("#image_path_preview").attr('src',image_path);
                                $("#image_path_preview").addClass('h-40');
                            }
                        }
                    } else {
                        if($("#"+index).length == 1) {
                            $("#"+index).val(value);
                        }
                    }

                });
                $(openModal).removeClass("hidden");
            }
        }


    });
}

function openDeletePopup(url) {
    $("#delete_certification_form").attr('action',url);
    $("#delete-certification-modal").removeClass('hidden');
}



