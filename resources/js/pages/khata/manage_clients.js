window.$ = window.jQuery = require('jquery');
require('jquery-validation');
require('devbridge-autocomplete');
import intlTelInput from "intl-tel-input";
require('../../components/validation_methods');

require('../../components/popup_modal');

$(function () {

    // manage client section
    $('.remove-client').on('click', function (e) {
        e.preventDefault();
        let $this = $(this);
        if (confirm('Are you sure you want to remove this client?')) {
            $this.parents('form').submit();
        } else {
            return false;
        }
    });

    // add client section
    $('#add-client').on('click', function () {
        $('#popup-modal-add-client').removeClass('hidden');

        // initializing phone number country selector
        var input = document.querySelector("#phone");
        intlTelInput(input, {
            utilsScript: '../js/utils/utils.js',
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

        validateAddClientForm('#addClientForm');
        $('#addExistingClient').validate({
            rules: {
                existingClient: {
                    reuired: true
                }
            },
            messages: {
                existingClient: {
                    required: "Please select client from the given list."
                }
            }
        });
    });

    $('#popup-modal-add-client #confirm-btn').on('click', function () {
        $('form#addClientForm').submit();
    });

});

function validateAddClientForm(formObj) {
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
            city: {
                required: true,
                minlength: 2,
            },
        },
        onkeyup: false,
        submitHandler: function (form) {
            let iti = window.intlTelInputGlobals.getInstance(document.querySelector("#phone"));
            $('input[name=phone_full]').val(iti.getNumber());
            submitAddClient(form);
        },
        errorPlacement: function (error, element) {
            if ($(element).attr('name') == 'phone') {
                $(error).insertAfter($(element).parent());
            } else {
                $(error).insertAfter(element);
            }
        },
        messages: {
            name: {
                required: "Name is required",
                minlength: "Please enter at least 3 characters.",
                maxlength: "Please enter not more than 50 characters."
            },
            phone: {
                required: "Phone number is required"
            },
            city: {
                required: "Please choose city from the list."
            }
        }
    });
}

function submitAddClient(form) {

    let submitBtn = $('#popup-modal-add-client #confirm-btn');

    $.ajax({
        method: 'POST',
        url: $(form).attr('action'),
        data: $(form).serialize(),
        beforeSend: function (xhr) {
            submitBtn.attr('disabled', true).addClass('btn-disabled');
        },
        success: function () {
            location.reload();
        },
        error: function (request, status, message) {
            let errors = request.responseJSON.errors;
            if (errors) {
                if (errors.phone_full) {
                    errors.phone = errors.phone_full;
                    delete errors.phone_full;
                }
                $(form).validate().showErrors(errors);
            }
            let alertDiv = $(form).find('.alert');
            alertDiv.text(request.responseJSON.message).show('slow');
            setTimeout(function () {
                alertDiv.text('').hide('slow');
            }, 10000);
        }
    }).always(function () {
        submitBtn.attr('disabled', false).removeClass('btn-disabled');
    });
}
