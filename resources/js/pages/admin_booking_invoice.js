window.$ = window.jQuery = require('jquery');
require('jquery-validation');
import 'jquery-datetimepicker';


var startDatePicker = $("#transaction_date").datetimepicker({
    format:  'Y-m-d',
    timepicker: false
});

$(document).ready(function() {

    $("#search_keywords").keyup(function() {
        var url = base_url + '/admin/subscriptions';
        ajaxSearch(url, true, ".search_results");
    });

    $(document).on('click', ".payment_received, .payment_made", function (event) {
        var currentStatus = $(this).attr('data-status');
        var invoiceId = $(this).attr('data-invoice-id');

        var formUrl = base_url + '/admin/warehousebookings/invoices/' + invoiceId;

        $("#payment_form input").each(function(key,element) {
            if($(element).attr('name') != "_token" && $(element).attr('name') != "_method") {
                $(element).val('');
            }
        });

        $("#payment_form textarea").val('');
        $("#payment_form select option").removeAttr('selected');

        $("#status option:contains(" + currentStatus + ")").attr('selected', 'selected');
        $("#status").trigger('change');

        $("#payment_form").attr('action', formUrl);
        $("#payment_form #amount").val($(this).attr('data-price'));
        if($(this).hasClass('payment_made')) {
            $("#payment_form #commission_percentage").val($(this).attr('data-commission-percentage'));
            $("#payment_form #commission_paid").val($(this).attr('data-commission-collected'));
            $("#payment_form #tax_percentage").val($(this).attr('data-tax-percentage'));
            $("#payment_form #tax_amount").val($(this).attr('data-tax-amount'));
            $("#payment_form #total_paid_to_owner").val($(this).attr('data-price') - $("#payment_form #tax_amount").val() - $("#payment_form #commission_paid").val());
            $("#payment_form #amount").attr('disabled', true);
        }
        $("#payment_form #amount").attr('disabled',false);
        $("#payment-received-modal").removeClass('hidden');
    });

    $(".close-modal").click(function() {
        $(".modal").each(function(index,element){

            if(!$(element).hasClass('hidden')) {
                $(element).addClass('hidden')
            }
        });
    });

    $("#update_status_btn").click(function(event) {

        if($('#payment_form').valid()) {
            $(this).attr('disabled',true);
            $("#payment_form").submit();
        }
    });

    $("#status").change(function() {
        $("#amount_div").hasClass('hidden') == false ? $("#amount_div").addClass('hidden') : '';
        $("#cancelled_div").hasClass('hidden') == false ? $("#cancelled_div").addClass('hidden') : '';
        $(".owner_paid").hasClass("hidden") == false ? $(".owner_paid").addClass("hidden") : '';

        if($(this).val() == "paid" && $("#amount_div").hasClass('hidden')) {
            $("#amount_div label").text("Booking Price");
            $(".owner_paid").removeClass("hidden");
            $("#amount_div").removeClass('hidden');
        } else if($(this).val() == "refunded" && $("#amount_div").hasClass('hidden')) {
            $("#amount_div label").text("Refunded Amount");
            $("#amount_div").removeClass('hidden');
            $("#cancelled_div label").text("Refunded and closed");
            $("#cancelled_div").removeClass('hidden');
        } else if($(this).val() == "cancelled" && $("#cancelled_div").hasClass('hidden')) {
            $("#cancelled_div label").text("Cancelled and closed");
            $("#cancelled_div").removeClass('hidden');
        } else if($(this).val() == "received" && $("#amount_div").hasClass('hidden')) {
            $("#amount_div label").text("Received Amount");
            $("#amount_div").removeClass('hidden');
        }
    });

    validatePaymentForm("#payment_form");

    $('#ref_image').change(function() {
        readURL(this);
        $('.remove_image').removeClass('hidden');
    });

    $('.remove_image').click(function() {
        $('#ref_image').val('');
        $('#ref_image_preview').attr('src', base_url + '/img/camera_icon.png');
        $('.remove_image').addClass('hidden');
    });

});


function validatePaymentForm(formObj) {
    $(formObj).validate({
        rules: {
            status: {
                required:true,
            },
            transaction_date: {
                required: true,
                dateISO: true,
            },
            ref_text: {
                required: true,
            },
            payment_method_id: {
                required: true,
                digits: true,
            },
            amount: {
                required: {
                    depends: function (element) {
                        return $('#status').val() == "received" || $('#status').val() == "paid" || $('#status').val() == "refunded";
                    }
                },
            }
        },
        messages: {
            status: {
                required:"Status is required",
            },
            transaction_date: {
                required: "Transaction date is required",
                dateISO: "Transaction date needs to be YYYY-MM-DD format",
            },
            ref_text: {
                required: "Description is required",
            },
            payment_method_id: {
                required: "Select one of the payment method used",
                digits: "Select a valid payment method",
            },
            amount: {
                required: "Amount paid or refunded is required"
            }
        }
    });
}

