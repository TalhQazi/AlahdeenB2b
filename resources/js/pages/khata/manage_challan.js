window.$ = window.jQuery = require('jquery');
require('jquery-validation');
import 'jquery-datetimepicker';
require('jquery-validation/dist/additional-methods.js');
require('../../components/validation_methods');

$(function () {

    // manage invoice section
    $('.remove-challan').on('click', function (e) {
        e.preventDefault();
        let $this = $(this);
        if (confirm('Are you sure you want to remove this challan?')) {
            $this.parents('form').submit();
        } else {
            return false;
        }
    });

    $('#client_id').on('change', function() {
        var to = $(this).find('option:selected').attr('data-client-name');
        $.ajax({
            method: 'GET',
            url: base_url + '/khata/client/' + $(this).val() + '/business',
            beforeSend: function (xhr) {
                $(this).attr('disabled', true).addClass('select-disabled');
            },
            success: function (response) {
                if(response.business_details != null) {
                  to += "/" + response.business_details.company_name;
                }
                $("#to").val(to);
            },
            error: function (request, status, message) {
                let errors = request.responseJSON.errors;
                if (errors) {
                    console.log(errors);
                }
            }
        }).always(function () {
            $(this).attr('disabled', false).removeClass('select-disabled');
        });
    }).trigger('change');


    $("#add_digital_signature_btn").click(function() {
      $("#digital_signature").click();
    });

    $("#challan_date").datetimepicker({
      format:'Y-m-d',
      timepicker: false,
      minDate:0
    });

    $("#digital_signature").change(function() {
      readURL(this);
    });

    validateChallanForm('#challan_form');

    $("#send_btn").click(function() {
        $("#send_via_chatbox").val(1);
        if($("#challan_form").valid()) {
            $("#challan_form").submit();
        }
    });

});

function validateChallanForm(formObj) {
  $(formObj).validate({
    ignore: [],
    rules: {
        digital_signature: {
            extension: "jpg|jpeg|png",
            maxsize: 500000,
        },
        client_id: {
            required: true,
        },
        challan_date: {
          required: true,
          dateISO: true,
        },
        items_included: {
          required: true,
        },
        no_of_pieces: {
          required: true,
        },
        weight: {
          required: true,
        },
        bilty_no: {
          required: true,
        },
        courier_name: {
          required: true
        }
    },
    messages: {
        digital_signature: {
            extension: "Only following image extendsions jpg, jpeg, png are allowed",
            maxsize: "Image size can not exceed 500Kb"
        },
        client_id: {
          required: "Select Client",
        },
        challan_date: {
          required: "Enter Challan date",
          dateISO: "Challan Date should be Y-m-d format"
        },
        items_included: {
          required: "Enter Items",
        },
        no_of_pieces: {
          required: "Enter No of Pieces",
        },
        weight: {
          required: "Enter weight",
        },
        bilty_no: {
          required: "Enter Bilty No"
        },
        courier_name: {
          required: "Enter Courier/Logistics Company Name"
        }
    },
    errorPlacement: function (error, element) {
        if ($(element).attr('name') == 'image_path') {
            $(error).insertAfter($(element).parents('#image_div'));
            $(error).addClass('ml-3');
        } else {
            // $(error).insertAfter($(element).parent());
            $(error).insertAfter(element);
            $(error).addClass('col-span-12 col-start-4');
        }

    },
    submitHandler: function(form) {
        // do other things for a valid form
        form.submit();
    }
  });
}

function readURL(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    var inputId = $(input).attr('id');

    reader.onload = function(e) {
      $('#' + inputId + '_preview').attr('src', e.target.result);
      if($('#' + inputId + '_div').hasClass('hidden')) {
        $('#' + inputId + '_div').removeClass('hidden');
      }
    }

    reader.readAsDataURL(input.files[0]); // convert to base64 string
  }
}
