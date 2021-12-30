window.$ = window.jQuery = require('jquery');
require('jquery-validation');
require('devbridge-autocomplete');
import intlTelInput from "intl-tel-input";
require('../components/validation_methods');

$(function () {
    $('#account_type').on('change', function () {
        switch (Number(this.value)) {
            case 1:
                $('#corporate').hide();
                $('#corporate :input').prop("disabled", true);
                $('#individual').hide();
                $('#individual :input').prop("disabled", true);
                break;
            case 2:
                $('#corporate').show();
                $('#corporate :input').prop("disabled", false);
                $('#individual').hide();
                $('#individual :input').prop("disabled", true);
                break;
            case 3:
                $('#corporate').hide();
                $('#corporate :input').prop("disabled", true);
                $('#individual').show();
                $('#individual :input').prop("disabled", false);
                break;
            case 4:
              $('#corporate').hide();
              $('#corporate :input').prop("disabled", true);
              $('#individual').show();
              $('#individual :input').prop("disabled", false);
              break;
        }
    });

    // initializing phone number country selector
    var input = document.querySelector("#phone");
    intlTelInput(input, {
        utilsScript: $.getScript(base_url + '/js/utils/utils.js'),
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

    $("#city").autocomplete({
        minChars: 3,
        serviceUrl: base_url + '/get_locations?loc_level=city',
        dataType: 'json',
        preserveInput: true,
        onSelect: function (suggestion) {
            $(this).val(suggestion.value);
            $("#city_id").val(suggestion.data.id);
        }
    });

    validateRegistrationForm('#registrationForm');
});


function validateRegistrationForm(formObj) {
    $(formObj).validate({
        rules: {
            name: {
                required: true,
                minlength: 3,
                maxlength: 50,
                lettersonly: true
            },
            email: {
                validEmail: true
            },
            phone: {
                required: true,
                validPhone: true
            },
            password: {
                required: true,
                minlength: 8
            },
            password_confirmation: {
                required: true,
                minlength: 8,
                equalTo: "#password"
            },
            city: {
                required: true,
                minlength: 2,
            },
            industry: {
                required: {
                    depends: function (element) {
                        return $('#account_type').val() == 2;
                    }
                },
                lettersonly: true,
            },
            address: {
                required: {
                    depends: function (element) {
                        return $('#account_type').val() == 3;
                    }
                },
            },
            job_freelance: {
                required: {
                    depends: function (element) {
                        return $('#account_type').val() == 3;
                    }
                },
            },
        },
        onkeyup: false,
        errorPlacement: function (error, element) {
            if ($(element).attr('name') == 'phone') {
                $(error).insertAfter($(element).parent());
            } else {
                $(error).insertAfter(element);
            }
        },
        messages: {
            name: {
                required: "First Name is required",
                minlength: "Please enter at least 3 characters.",
                maxlength: "Please enter not more than 50 characters."
            },
            phone: {
                required: "Phone number is required"
            },
            password: {
                required: "Password is required",
                minlength: "Password should be of atleast 8 characters"
            },
            password_confirmation: {
                required: "Password is required",
                minlength: "Password should be of atleast 8 characters",
                equalTo: "Confirm Password should match password"
            }
        }
    });
}
