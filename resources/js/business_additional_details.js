window.$ = window.jQuery = require('jquery');
require('jquery-validation');
require('devbridge-autocomplete');
import intlTelInput from "intl-tel-input";

$(document).ready(function() {
    $("#business_states_input").autocomplete({
        minChars: 3,
        serviceUrl: base_url + '/get_locations?loc_level=state',
        dataType: 'json',
        preserveInput: false,
        onSelect: function (suggestion) {
            var suggestionSpanId = (suggestion.value).replace(/ /gi, "_").toLowerCase().replace(/[^\w\s]/gi, '_');
            var inputId = $(this).attr('data-target-div');
            if($(this).attr('id') == "business_states_input") {
                if( $("#"+suggestionSpanId).length < 1) {

                    var selectedProductHtml = "<span id='" + suggestionSpanId + "' class='inline-block rounded-full text-gray-600 bg-green-200 px-4 py-2 text-xs font-bold mr-3'>"+
                                                    suggestion.value +
                                                    "<i class='fa fa-times ml-2 delete-keyword' data-prodservice-id='' data-keyword='" + suggestion.value + "' data-target-parent='" + suggestion.value + "'></i>"+
                                                    "<input type='hidden' name='business_states[]' value='" + suggestion.value + "'>" +
                                                "</span>";
                    $(inputId).append(selectedProductHtml);
                }
            }

            $(this).val("");
        }
    });


    $(".keywords").autocomplete({
        minChars: 3,
        serviceUrl: base_url + '/get_locations?loc_level=city',
        dataType: 'json',
        preserveInput: false,
        onSelect: function (suggestion) {
            var suggestionSpanId = (suggestion.value).replace(/ /gi, "_").toLowerCase().replace(/[^\w\s]/gi, '_');
            var inputId = $(this).attr('data-target-div');
            if($(this).attr('id') == "business_incl_cities_input") {
                if( $("#"+suggestionSpanId).length < 1) {

                    var selectedProductHtml =   "<span id='" + suggestionSpanId + "' class='inline-block rounded-full text-gray-600 bg-green-200 px-4 py-2 text-xs font-bold mr-3'>"+
                                                    suggestion.value +
                                                    "<i class='fa fa-times ml-2 delete-keyword' data-prodservice-id='' data-keyword='" + suggestion.value + "' data-target-parent='" + suggestion.value + "'></i>"+
                                                    "<input type='hidden' name='included_cities[]' value='" + suggestion.value + "'>" +
                                                "</span>";
                    $(inputId).append(selectedProductHtml);
                }
            } else {

                if( $("#" + suggestionSpanId).length < 1) {
                    var selectedProductHtml =   "<span id='" + suggestionSpanId + "' class='inline-block rounded-full text-gray-600 bg-green-200 px-4 py-2 text-xs font-bold mr-3'>"+
                                                    suggestion.value +
                                                    "<i class='fa fa-times ml-2 delete-keyword' data-prodservice-id='' data-keyword='" + suggestion.value + "' data-target-parent='" + suggestion.value + "'></i>"+
                                                    "<input type='hidden' name='excluded_cities[]' value='" + suggestion.value + "'>" +
                                                "</span>";
                    $(inputId).append(selectedProductHtml);
                }
            }

            $(this).val("");
        }
    });

    $(document).on("click", ".delete-keyword", function() {
        $(this).parent('span').remove();
    });

    $("#logo").change(function() {
        readURL(this);
    });

    $(".company_photo").change(function() {
        readURL(this);
    });

    $("#add_contact_btn").click(function() {
        $("#business-contact-form input").each(function(key, element){
            if($(element).attr('id') != "business_id" && $(element).attr('name') != "_token") {
                $(element).val('');
            }
        });
        $("#business-contact-modal").removeClass("hidden");
    });

    // initializing phone number country selector
    var input = document.querySelector("#cell_no");
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

    $(document).on('click', '.edit_contact_btn', function(event) {
        event.preventDefault();
        openEditPopup($(this).attr('data-url'));
    });

    $(document).on('click', '.delete_contact_btn', function(event) {
        event.preventDefault();
        openDeletePopup($(this).attr('data-url'));
    });

    $(".close-modal").click(function() {
        $(".modal").each(function(index,element){
            console.log(element);
            if(!$(element).hasClass('hidden')) {
                $(element).addClass('hidden')
            }
        });
    });

    validateBusinessContactForm('#business-contact-form');

});

function readURL(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      var inputId = $(input).attr('id');
      var photoNo = inputId.replace('photo_','');

      reader.onload = function(e) {
        $('#' + inputId + '_preview').attr('src', e.target.result);
        if(!$('#' + inputId + '_preview').hasClass('h-40')) {
            $('#' + inputId + '_preview').addClass('h-40');
        }

        $('#company_photo_id_' + photoNo).remove();
      }

      reader.readAsDataURL(input.files[0]); // convert to base64 string
    }
}


function validateBusinessContactForm(formObj) {

    $(formObj).validate({
        rules: {
            division: {
                required: true,
            },
            contact_person: {
                required: true,
                lettersonly: true,
                minlength: 3,
                maxlength: 50,
            },
            designation: {
                required: true,
            },
            location: {
                required: true,
            },
            postal_code: {
                required: true,
            },
            cell_no: {
                required: true,
                validPhone: true
            },

        },
        messages: {
            division: {
                required: "Please select division",
            },
            contact_person: {
                required: "Contact Person is required",
                minlength: "Please enter at least 3 characters.",
                maxlength: "Please enter not more than 50 characters."
            },
            designation: {
                required: "Designation is required",
            },
            location: {
                required: "Location is required",
            },
            postal_code: {
                required: "Postal Code is required",
            },
            cell_no: {
                required: "Mobile/Cell number is required"
            }
        },
        errorPlacement: function (error, element) {
            if ($(element).attr('name') == 'cell_no') {
                $(error).insertAfter($(element).parent());
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


jQuery.validator.addMethod(
    "lettersonly",
    function (value, element) {
        return this.optional(element) || /^[a-z ]+$/i.test(value);
    },
    "Only Letters are allowed."
);

jQuery.validator.addMethod("validPhone", function (value, element) {
    let iti = window.intlTelInputGlobals.getInstance(document.querySelector("#cell_no"));
    return iti.isValidNumber();
}, "Please provide valid phone number.");

function openEditPopup(url) {
    $.ajax({
        method: 'GET',
        url: url,
        dataType: 'json',
        success: function(response) {
            if(response.data != "") {
                $.each(response.data, function(index,value) {
                    if(index == "id") {
                        $("#contact_id").val(value);
                    } else {
                        if($("#"+index).length == 1) {
                            $("#"+index).val(value);
                        }
                    }

                });
                $("#business-contact-modal").removeClass("hidden");
            }
        }


    });
}

function openDeletePopup(url) {
    $("#delete_contact_form").attr('action',url);
    $("#delete-modal").removeClass('hidden');
}



