window.$ = window.jQuery = require('jquery');
require('jquery-validation');
require ('jquery-mask-plugin');
import intlTelInput from "intl-tel-input";

$(document).ready(function() {

    // initializing phone number country selector
    var input = document.querySelector("#phone_number");
    intlTelInput(input, {
        utlisScript: $.getScript(base_url + '/js/utils/utils.js'),
        separateDialCode: true,
        placeholderNumberType: "MOBILE",
        initialCountry: "auto",
        hiddenInput: "phone_full",
        geoIpLookup: function (success, failure) {
            $.get("https://ipinfo.io?token=37910420e39b85", function () { }, "jsonp").always(function (resp) {
                var countryCode = (resp && resp.country) ? resp.country : "pk";
                success(countryCode);
            });
        },
    });

    $("#cnic").mask('00000-0000000-0', {placeholder: '_____-________-_'});

    validateBusinessForm('#business_form');

});




function validateBusinessForm(formObj) {

    $(formObj).validate({
        rules: {
            company_name: {
                required: true,
                minlength: 3,
                maxlength: 150,
            },
            address: {
                required: true,
                minlength: 3
            },
            phone_number: {
                required: true,
                validPhone: true
            },
            year_of_establishment: {
                required: true,
                digitsonly: true,
                minlength: 4,
                maxlength: 4
            },
            no_of_employees: {
                required: true,
                digitsonly: true,
            },
            annual_turnover: {
                required: true,
            },
            cnic: {
                minlength: 13,
                maxlength: 15,
            },

        },
        messages: {
            company_name: {
                required: "Please select division",
                minlength: "Please enter at least 3 characters.",
                maxlength: "Please enter not more than 150 characters."
            },
            address: {
                required: "Address is required",
                minlength: "Please enter at least 3 characters.",
            },
            phone_number: {
                required: "Phone number is required",
            },
            year_of_establishment: {
                required: "Year of establishment is required",
                minlength: "At least 4 digits required",
                maxlength: "Can not enter more then 4 digits"
            },
            no_of_employees: {
                required: "Number of employees is required",
            },
            annual_turnover: {
                required: "Annual turnover is required",
                digitsonly: true
            },
            cnic: {
                minlength: "CNIC number should consist of 15 digits including -"
            }
        },
        errorPlacement: function (error, element) {
            if ($(element).attr('name') == 'phone_number') {
                $(error).insertAfter($(element).parent());
            } else {
                $(error).insertAfter(element);
            }
        },
        submitHandler: function(form) {
            // do other things for a valid form
            var dialCodeText = $(".iti__selected-dial-code").text();
            $("#phone_number").val(dialCodeText + $("#phone_number").val());
            // $("#cnic").val($("#cnic").cleanVal());
            $(formObj + ' input:disabled').each(function(e) {
                $(this).removeAttr('disabled');
            });
            form.submit();
        }
    });
}


jQuery.validator.addMethod(
    "lettersonly",
    function (value, element) {
        return this.optional(element) || /^[a-z ]+$/i.test(value);
    },
    "Only Letters are allowed."
);

jQuery.validator.addMethod(
    "digitsonly",
    function (value, element) {
        return this.optional(element) || /^[0-9 ]+$/i.test(value);
    },
    "Only Letters are allowed."
);

jQuery.validator.addMethod("validPhone", function (value, element) {
    let iti = window.intlTelInputGlobals.getInstance(document.querySelector("#phone_number"));
    return iti.isValidNumber();
}, "Please provide valid phone number.");



