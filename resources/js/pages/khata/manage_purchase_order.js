window.$ = window.jQuery = require('jquery');
require('jquery-validation');
import 'jquery-datetimepicker';
require('jquery-validation/dist/additional-methods.js');
require('../../components/validation_methods');

$(function () {

    validatePurchaseOrderForm('#purchase_order_form');

    // manage invoice section
    $('.remove-purchase-order').on('click', function (e) {
      e.preventDefault();
      let $this = $(this);
      if (confirm('Are you sure you want to remove this purchase order?')) {
          $this.parents('form').submit();
      } else {
          return false;
      }
  });

    $("#po_date").datetimepicker({
      format:'Y-m-d',
      timepicker: false,
    });

    $("#po_delivery_date").datetimepicker({
      format:'Y-m-d H:i'
    });

    $("#po_company_logo").click(function() {
      $("#logo_path").click();
    });

    $("#q").keyup(function() {
      if($(this).val().length < 3) {
        return false;
      } else {
        $.ajax({
          url: base_url + '/khata/purchase-order?keywords='+$(this).val(),
          dataType: 'json',
          method: 'get',
          success: function(response) {
            if(response.length > 0) {
              populateTable(response);
            }
          }
        });
      }
    });

    $("#po_attachment").click(function() {
      $("#attachment").click();
    });

    $("#attachment").change(function() {
      readURL(this);
    });

});

function validatePurchaseOrderForm(formObj) {
  $(formObj).validate({
    ignore: [],
    rules: {
        logo_path: {
            required: function(element) {
                return $("#po_company_logo").length > 0 ? true : false;
            },
            extension: "jpg|jpeg|png",
            maxsize: 500000,
        },
        po_number: {
            required: true,
        },
        po_date: {
          required: true,
          dateISO: true,
        },
        po_delivery_date: {
          required: true,
        },
        order_description: {
          required: true,
        },
        payment_details: {
          required: true,
        }
    },
    messages: {
        logo_path: {
            required: "Image needs to be uploaded",
            extension: "Only following image extendsions jpg, jpeg, png are allowed",
            maxsize: "Image size can not exceed 500Kb"
        },
        po_number: {
          required: "Enter PO number",
        },
        po_date: {
          required: "Enter PO date",
          dateISO: "PO Date should be Y-m-d format"
        },
        po_delivery_date: {
          required: "Enter PO delivery date",
        },
        order_description: {
          required: "Enter PO details",
        },
        payment_details: {
          required: "Enter Payment Details",
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

function submitAddClient(form) {

    let submitBtn = $('#popup-modal-add-client #confirm-btn');

    $.ajax({
        method: 'POST',
        url: base_url + $(form).attr('action'),
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

function populateTable(response) {
  var html = '';
  $(response).each(function(key, purchase_order) {
    html +=  `<tr class="hover:bg-gray-100 border-b border-gray-200 py-10">
    <td class="px-4 py-4">${purchase_order.po_date}</td>
    <td class="px-4 py-4">${purchase_order.number}</td>
    <td class="px-4 py-4">${purchase_order.po_delivery_date}</td>
    <td class="px-4 py-4">${purchase_order.order_description}</td>
    <td class="px-4 py-4">${purchase_order.payment_details}</td>
    <td class="px-4 py-4">Attachment Link Will Come Here</td>
    <td class="px-4 py-4">${purchase_order.created_by.name}</td>
    <td class="px-4 py-4 controls">
        <form action="${base_url+'/khata/purchase-order/' + purchase_order.id}"
            method="POST">
            <input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr('content')}">
            <span>
                <a href="${base_url+'/khata/purchase-order/' + purchase_order.id +'/edit'}" title="Edit Purchase Order">
                    <i class="far fa-pencil fa-lg"></i>
                </a>
            </span>
            <input type="hidden" name="_method" value="DELETE">
            <span>
                <button class="remove-client" type="submit" title="{{ __('Remove Purchase Order') }}">
                    <i class="far fa-trash fa-lg"></i>
                </button>
            </span>
        </form>
    </td>
  </tr>`;
  });

  $("#search_results").html(html);
}

function readURL(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    var inputId = $(input).attr('id');
    reader.onload = function(e) {
      if(input.files[0].type.indexOf('pdf') != -1) {
        $('#' + inputId + '_preview').attr('src', base_url + '/img/docs.png');
      } else {
        $('#' + inputId + '_preview').attr('src', e.target.result);
      }
      if($('#' + inputId + '_div').hasClass('hidden')) {
        $('#' + inputId + '_div').removeClass('hidden');
      }
    }

    reader.readAsDataURL(input.files[0]); // convert to base64 string
  }
}
