window.$ = window.jQuery = require('jquery');
require('jquery-validation');
require('devbridge-autocomplete');
import intlTelInput from "intl-tel-input";
import 'jquery-datetimepicker';
require('../../components/validation_methods');

require('../../components/popup_modal');

var promotions = [];

$(function () {


    // manage invoice section
    $('.remove-invoice').on('click', function (e) {
        e.preventDefault();
        let $this = $(this);
        if (confirm('Are you sure you want to remove this invoice?')) {
            $this.parents('form').submit();
        } else {
            return false;
        }
    });

    $('#client_id').on('change', function() {
        $.ajax({
            method: 'GET',
            url: base_url + '/khata/client/' + $(this).val() + '/business',
            beforeSend: function (xhr) {
                $(this).attr('disabled', true).addClass('select-disabled');
            },
            success: function (response) {
                var html = `<div class="bd-li">
                                <span class="li-label">Address</span>:
                                <span class="li-value">${response.business_details ? response.business_details.address : response.client.address ? response.client.address : 'NA' }</span>
                            </div>
                            <div class="bd-li">
                                <span class="li-label">City|State</span>:
                                <span class="li-value">${response.business_city.city}, ${response.business_city.admin_name}</span>
                            </div>
                            <div class="bd-li">
                                <span class="li-label">Zip Code</span>:
                                <span class="li-value">${response.business_details ? response.business_details.zip_code : ''}</span>
                            </div>
                            <div class="bd-li">
                                <span class="li-label">Phone</span>:
                                <span class="li-value">${response.business_details ? response.business_details.phone_number : response.client.phone}</span>
                            </div>`;
                $('.buyer-business-details').append(html);
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

    // add invoice section
    $('#add-invoice').on('click', function () {
        $('#popup-modal-add-invoice').removeClass('hidden');

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

    $(document).on('focus', '.product_name', function () {
      $(this).autocomplete({
        minChars: 3,
        serviceUrl: base_url + '/product/get_products?logged_in_user=1&promotion=1',
        dataType: 'json',
        onSelect: function (suggestion) {
            var itemNumber = $(this).attr('id').replace('item_name_', '');
            $(`#item_id_${itemNumber}`).val(suggestion.data.id);
            $(`#item_rate_${itemNumber}`).val(suggestion.data.price);

            if(suggestion.data.promotion_offer) {
                if(!promotions[suggestion.data.promotion_offer.promotional_product_id])
                    promotions[suggestion.data.promotion_offer.promotional_product_id] = suggestion.data.promotion_offer;
            }

            console.log(promotions);

        }
      });
    });

    $('#add_more_btn').click(function(event) {
      event.preventDefault();
      var rowNumber = $('.product-details table tbody tr').length + 1;
      var html = `<tr class="hover:bg-gray-100 border-b border-gray-200 py-10">
                    <td class="px-4 py-4">
                        ${rowNumber}
                    </td>
                    <td class="px-4 py-4">
                        <input class="text-base border-b border-gray-300 focus:outline-none focus:border-gray-500 w-40 product_name" type="text" name="item[${rowNumber}][name]" id="item_name_${rowNumber}">
                        <input type="hidden" name="item[${rowNumber}][id]" id="item_id_${rowNumber}">
                        <input type="hidden" name="item[${rowNumber}][promotion_id]" id="item_promotion_id_${rowNumber}">
                    </td>
                    <td class="px-4 py-4">
                        <input class="text-base border-b border-gray-300 focus:outline-none focus:border-gray-500 w-40" type="text" name="item[${rowNumber}][code]" id="item_code_${rowNumber}">
                    </td>
                    <td class="px-4 py-4">
                        <input class="text-base border-b border-gray-300 focus:outline-none focus:border-gray-500 w-40 qty" type="text" name="item[${rowNumber}][qty]" id="item_qty_${rowNumber}">
                    </td>
                    <td class="px-4 py-4">
                        <select name="item[${rowNumber}][unit]" id="item_unit_${rowNumber}" class="text-base border-b border-gray-300 focus:outline-none focus:border-gray-500 w-40">
                    </td>
                    <td class="px-4 py-4">
                        <input class="text-base border-b border-gray-300 focus:outline-none focus:border-gray-500 w-40 rate" type="text" name="item[${rowNumber}][rate]" id="item_rate_${rowNumber}">
                    </td>
                    <td class="px-4 py-4">
                        <input class="text-base border-b border-gray-300 focus:outline-none focus:border-gray-500 w-40 gst" type="text" name="item[${rowNumber}][gst]" id="item_gst_${rowNumber}" value="0">
                    </td>
                    <td class="px-4 py-4" id="item_tax_exc_amount_${rowNumber}">
                        0
                    </td>
                    <td class="px-4 py-4" id="item_tax_amount_${rowNumber}">
                        0
                    </td>
                    <td class="px-4 py-4" id="item_total_amount_${rowNumber}">
                        0
                    </td>
                </tr>`;
        $('#products_tbody').append(html);
        $('#item_unit_1').find('option').clone().appendTo(`#item_unit_${rowNumber}`);
    });

    $("#add_terms_btn").click(function() {
      $("#terms_and_conditions").removeClass('hidden');
    });

    $("#add_tax_certificate_btn").click(function() {
      $("#tax_certificate").click();
    });

    $("#add_digital_signature_btn").click(function() {
      $("#digital_signature").click();
    });

    // $("#digital_signature").change(function() {
    //   readURL(this);
    // });

    $("#add_purchase_order").click(function() {
      $("#purchase_order").click();
    });

    $("#add_delivery_challan").click(function() {
      $("#delivery_challan").click();
    });

    $("#add_shipment_receipt").click(function() {
      $("#shipment_receipt").click();
    });

    $("#purchase_order, #digital_signature, #delivery_challan, #shipment_receipt, #tax_certificate").change(function() {
      readURL(this);
    });

    $("#apply_promotion_btn").click(function() {
        event.preventDefault();
        var rowAppliedUntil = $("#apply_promotion_btn").attr("data-row-applied");

        applyPromotions(rowAppliedUntil);

        var rowsParsed = $(".product-details table tbody tr").length;
        $("#apply_promotion_btn").attr("data-row-applied", rowsParsed);

    });

    $(document).on('keyup', '.qty, .rate', function() {
        if($(this).hasClass('qty')) {
            var rowNumber = $(this).attr('id').replace("item_qty_", "");
        } else if($(this).hasClass('rate')) {
            var rowNumber = $(this).attr('id').replace("item_rate_", "");
        }


        var productRate = Number($(`#item_rate_${rowNumber}`).val());
        var qtyRequired = Number($(`#item_qty_${rowNumber}`).val());


        var taxExcAmount = qtyRequired * productRate;
        var taxAmount = taxExcAmount * ( Number($(`#item_gst_${rowNumber}`).val())  / 100 );
        $(`#item_tax_exc_amount_${rowNumber}`).text(taxExcAmount);
        $(`#item_tax_amount_${rowNumber}`).text(taxAmount);
        $(`#item_total_amount_${rowNumber}`).text(taxExcAmount + taxAmount);

        calcTotals();
    });

    $(document).on('keyup', '.gst', function() {
      var rowNumber = $(this).attr('id').replace("item_gst_", "");
      var taxExcAmount =  Number($(`#item_rate_${rowNumber}`).val()) * Number($(`#item_qty_${rowNumber}`).val());
      var taxAmount = taxExcAmount * ( Number($(this).val())  / 100 );
      $(`#item_tax_amount_${rowNumber}`).text(taxAmount);
      $(`#item_total_amount_${rowNumber}`).text(taxExcAmount + taxAmount);

      calcTotals();
    });

    $("#freight_charges").keyup(function() {
      calcTotals();
    });

    $("#invoice_date").datetimepicker({
      format:'Y-m-d',
      timepicker: false
    });

    $("#payment_due_date").datetimepicker({
      format:'Y-m-d',
      minDate: 0,
      timepicker: false
    });

    $("#delivery_date").datetimepicker({
      format:'Y-m-d H:i'
    });

    $(".mail_invoice").click(function() {
      var invoiceId = $(this).attr('data-invoice-id');
      $.ajax({
        url: base_url + '/khata/invoice/' + invoiceId + '/send',
        dataType: 'json',
        method: 'get',
        success: function(response) {
          console.log(response);
        }
      });
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

function calcTotals() {
  var numberOfProducts = $('.product-details table tbody tr').length;
  var amount = 0;
  var salesTax = 0;
  var freightCharges = Number($("#freight_charges").val());
  for(var i = 1; i <= numberOfProducts; i++) {
    amount += Number($(`#item_tax_exc_amount_${i}`).text());
    salesTax += Number($(`#item_tax_amount_${i}`).text());
  }
  $("#amount").val(amount);
  $("#total_sales_tax").val(salesTax);
  $("#total").val(amount + salesTax + freightCharges);

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

function applyPromotions(rowAppliedUntil) {
    $(".product-details table tbody tr").each(function(key, row) {
        if(rowAppliedUntil <= key) {

            var rowNumber = key + 1;
            var productId = $(`#item_id_${rowNumber}`).val();
            var productRate = Number($(`#item_rate_${rowNumber}`).val());
            var qtyRequired = Number($(`#item_qty_${rowNumber}`).val());

            if(promotions[productId] && promotions[productId].by_no_of_units == 1) {
                if(qtyRequired != "") {
                    if(qtyRequired <= promotions[productId].remaining_no_of_units) {
                        var promotionalProductRate = productRate - ((productRate * promotions[productId].discount_percentage) / 100);
                        $(`#item_rate_${rowNumber}`).val(promotionalProductRate);
                        $(`#item_rate_${rowNumber}`).parent('td').append('<span title="Promotion Applied"><i class="fas fa-info-circle"></i></span>');
                        $(`#item_promotion_id_${rowNumber}`).val(promotions[productId].id);
                        $('.qty').trigger('keyup');
                    } else {
                        var promotionalProductRate = productRate - ((productRate * promotions[productId].discount_percentage) / 100);
                        $(`#item_rate_${rowNumber}`).val(promotionalProductRate);
                        $(`#item_rate_${rowNumber}`).parent('td').append('<span title="Promotion Applied"><i class="fas fa-info-circle"></i></span>');
                        $(`#item_qty_${rowNumber}`).val(promotions[productId].remaining_no_of_units);
                        $(`#item_promotion_id_${rowNumber}`).val(promotions[productId].id);

                        $("#add_more_btn").trigger('click');

                        $(`#item_name_${$(".product-details table tbody tr").length}`).val($(`#item_name_${rowNumber}`).val());
                        $(`#item_id_${$(".product-details table tbody tr").length}`).val($(`#item_id_${rowNumber}`).val());
                        $(`#item_code_${$(".product-details table tbody tr").length}`).val($(`#item_code_${rowNumber}`).val());
                        $(`#item_unit_${$(".product-details table tbody tr").length}`).val($(`#item_unit_${rowNumber}`).val());

                        $(`#item_qty_${$(".product-details table tbody tr").length}`).val(qtyRequired - promotions[productId].remaining_no_of_units);
                        $(`#item_rate_${$(".product-details table tbody tr").length}`).val(productRate);
                        $('.qty').trigger('keyup');
                    }
                }
            }
        }
    });
}
