window.$ = window.jQuery = require('jquery');
require('jquery-validation');
require('jquery-validation/dist/additional-methods.js')
require('../components/validation_methods');

$(document).ready(function() {

    $("#image_path").change(function() {
        readURL(this);
    });

    validateAdsForm('#ad_create_form');
});


function validateAdsForm(formObj) {

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
            display_order: {
                required: true,
                digitsonly: true,
            },
        },
        messages: {
            image_path: {
                required: "Image needs to be uploaded",
                extension: "Only following image extendsions jpg, jpeg, png are allowed",
                maxsize: "Image size can not exceed 500Kb"
            },
            display_order: {
                required: "Please enter display order",
                digitsonly: "Only number can be entered as display order"
            },
        },
        errorPlacement: function (error, element) {
            if ($(element).attr('name') == 'image_path') {
                $(error).insertAfter($(element).parents('#image_div'));
                $(error).addClass('ml-3');
            } else {
                $(error).insertAfter(element);
            }

        },
        submitHandler: function(form) {
            // do other things for a valid form
            form.submit();
        }
    });
}
