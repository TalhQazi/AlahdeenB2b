window.$ = window.jQuery = require('jquery');
require('jquery-validation');
require('jquery-validation/dist/additional-methods');
require('jquery-datetimepicker');

var currentStep = 0;
var steps = $('.step-div');

$(document).ready(function() {

    $("#dob").datetimepicker({
        format:'Y-m-d',
        timepicker: false
    });

    $("#date_of_expiry").datetimepicker({
        format: 'Y-m-d',
        minDate: 0,
        timepicker: false
    });

    $("#next_step").click(function(e) {
        e.preventDefault();
        validateForm('#driver_info_form');

        if( validateCurrentForm() ) {
            if(currentStep == 0) {
                $("#previous_step").removeClass('hidden');
            } else if (currentStep == (steps.length - 1)) {
                $("#driver_info_form").submit();
                return false;
            }

            hideTab(currentStep);
            currentStep++;
            showTab(currentStep);
        }
    });


    $("#previous_step").click(function(e) {
        e.preventDefault();

        hideTab(currentStep);
        currentStep--;
        showTab(currentStep);

        if(currentStep == 0) {
            $("#previous_step").addClass('hidden');
        }

    });


    $(".image_path").change(function() {
        readURL(this);
    });

    $('.remove_image').click(function() {
       $(this).parents('.warehouse-img-div').remove();
    });

});

function hideTab(n) {
    var currentDiv = $(steps[n]).attr('id');
    $(`#${currentDiv}`).addClass('hidden');
}

function showTab(n) {

    var targetDiv = $(steps[n]).attr('id');
    $(`#${targetDiv}`).removeClass('hidden');
    var title = $(`#${targetDiv}`).attr('data-title');

    if (n == (steps.length - 1)) {
        $("#next_step").text("Submit");
    } else {
        $("#next_step").text("Next");
    }

    $(".card-header").text(title);
}

function validateCurrentForm() {
    var formStep = $('.step-div').attr('id');
    if(formStep == "about") {
        return $('#driver_info_form').valid();
    } else if(formStep == "license") {
        return $('#driver_info_form').valid();
    }
}

function validateForm(formObj) {

    $(formObj).validate({
        ignore: [],
        rules: {
            image_path: {
                required: function(element) {
                    return $("#image_path_preview").attr('src').indexOf('camera_icon.png') != -1 ? true : false;
                },
                extension: "jpg|jpeg|png",
                maxsize: 500000,
            },
            dob: {
                required: true,
                dateISO: true,
            },
            license_path: {
                required: function(element) {
                    return !$("#license").hasClass('hidden') && $("#license_path_preview").attr('src').indexOf('camera_icon.png') != -1 ? true : false;
                },
                extension: "jpg|jpeg|png",
                maxsize: 500000,
            },
            liscense_no: {
                required: function(element) {
                    return !$("#license").hasClass('hidden') ? true : false;
                },
            },
            date_of_expiry: {
                required: function(element) {
                    return !$("#license").hasClass('hidden') ? true : false;
                },
            },
            cnic_front_path: {
                required: function(element) {
                    return !$("#cnic").hasClass('hidden') && $("#cnic_front_path_preview").attr('src').indexOf('camera_icon.png') != -1 ? true : false;
                },
                extension: "jpg|jpeg|png",
                maxsize: 500000,
            },
            cnic_back_path: {
                required: function(element) {
                    return !$("#cnic").hasClass('hidden') && $("#cnic_front_path_preview").attr('src').indexOf('camera_icon.png') != -1 ? true : false;
                },
                extension: "jpg|jpeg|png",
                maxsize: 500000,
            },
            vehicle_image: {
                required: function(element) {
                    return !$("#vehicle_info").hasClass('hidden') && $("#vehicle_image_preview").attr('src').indexOf('camera_icon.png') != -1 ? true : false;
                },
                extension: "jpg|jpeg|png",
                maxsize: 500000,
            },
            vehicle_reg_certificate: {
                required: function(element) {
                    return !$("#vehicle_info").hasClass('hidden') && $("#vehicle_reg_certificate_preview").attr('src').indexOf('camera_icon.png') != -1 ? true : false;
                },
                extension: "jpg|jpeg|png",
                maxsize: 500000,
            },
            vehicle_health_certificate: {
                required: function(element) {
                    return !$("#vehicle_info").hasClass('hidden') && $("#vehicle_health_certificate_preview").attr('src').indexOf('camera_icon.png') != -1 ? true : false;
                },
                extension: "jpg|jpeg|png",
                maxsize: 500000,
            },
            company: {
                required: function(element) {
                    return !$("#vehicle_info").hasClass('hidden') ? true : false;
                },
            },
            number_plate_no: {
                required: function(element) {
                    return !$("#vehicle_info").hasClass('hidden') ? true : false;
                }
            }

        },
        messages: {
            image_path: {
                required: "Image needs to be uploaded",
                extension: "Only following image extendsions jpg, jpeg, png are allowed",
                maxsize: "Image size can not exceed 500Kb"
            },
            dob: {
                required: "Date of birth is required",
                number: "Enter date in the following format YYYY-MM-DD"
            },
            license_path: {
                required: "Image needs to be uploaded",
                extension: "Only following image extendsions jpg, jpeg, png are allowed",
                maxsize: "Image size can not exceed 500Kb"
            },
            liscense_no: {
                required: "License number is required"
            },
            date_of_expiry: {
                required: "Date of expiry is required"
            },
            cnic_front_path: {
                required: "Image needs to be uploaded",
                extension: "Only following image extendsions jpg, jpeg, png are allowed",
                maxsize: "Image size can not exceed 500Kb"
            },
            cnic_back_path: {
                required: "Image needs to be uploaded",
                extension: "Only following image extendsions jpg, jpeg, png are allowed",
                maxsize: "Image size can not exceed 500Kb"
            },
            vehicle_image: {
                required: "Image needs to be uploaded",
                extension: "Only following image extendsions jpg, jpeg, png are allowed",
                maxsize: "Image size can not exceed 500Kb"
            },
            vehicle_reg_certificate: {
                required: "Image needs to be uploaded",
                extension: "Only following image extendsions jpg, jpeg, png are allowed",
                maxsize: "Image size can not exceed 500Kb"
            },
            vehicle_health_certificate: {
                required: "Image needs to be uploaded",
                extension: "Only following image extendsions jpg, jpeg, png are allowed",
                maxsize: "Image size can not exceed 500Kb"
            },
            company: {
                required: 'Company name is required'
            },
            number_plate_no: {
                required: 'Number plate no is required',
            }
        },
        errorPlacement: function (error, element) {
            if ($(element).hasClass('image_path')) {
                $(error).insertAfter($(element).parents('.image_div'));
                $(error).addClass('ml-3');
            } else {
                $(error).insertAfter(element);
            }

        },
    });

}

function readURL(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      var inputId = $(input).attr('id');

      reader.onload = function(e) {
        $('#' + inputId + '_preview').attr('src', e.target.result);
        if(!$('#' + inputId + '_preview').hasClass('h-40')) {
            $('#' + inputId + '_preview').addClass('h-40');
        }
      }

      reader.readAsDataURL(input.files[0]); // convert to base64 string
    }
}





