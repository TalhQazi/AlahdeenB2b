$(function() {

    $("#promotional_product").autocomplete({
        minChars: 3,
        serviceUrl: base_url + '/product/get_products?logged_in_user=1&product_defination=1',
        dataType: 'json',
        preserveInput: false,
        onSelect: function (response) {
            var data = response.data;
            data.productDefination != null ? $("#promotional_product_code").val(data.productDefination.product_code) : $("#promotional_product_code").val('');
            $("#promotional_product_id").val(data.id);
            $("#promotional_product_price").val(data.price);
        }
    });

    $("#by_date").click(function() {
        if( $(".by_date_fields").hasClass('hidden') ) {
            $(".by_date_fields").removeClass('hidden')
        } else {
            $(".by_date_fields").addClass('hidden')
        }
    });

    $("#by_no_of_units").click(function() {
        if( $(".by_no_of_units_field").hasClass('hidden') ) {
            $(".by_no_of_units_field").removeClass('hidden')
        } else {
            $(".by_no_of_units_field").addClass('hidden')
        }
    });

    $("#start_date").datetimepicker({
        format:'Y-m-d',
        timepicker: false,
        onShow:function( ct ){
            this.setOptions({
                maxDate:$('#end_date').val() ? $('#end_date').val() : false
            })
        }
    });

    $("#end_date").datetimepicker({
        format:'Y-m-d',
        timepicker: false,
        onShow:function( ct ){
            this.setOptions({
                minDate:$('#start_date').val() ? $('#start_date').val() : false
            })
        }
    });

    $("#promotional_discount_percentage").keyup(function() {
        var pricePerUnit = $("#promotional_product_price").val();
        var discountPercentage = $(this).val();
        if(pricePerUnit != "" && discountPercentage != "") {
            pricePerUnit = Number(pricePerUnit);
            var discountValue = pricePerUnit * ( Number(discountPercentage) / 100 );
            $("#promotional_discount_value").val(discountValue);
        } else {
            $("#promotional_discount_value").val('');
        }
    });

    $("#promotional_discount_value").keyup(function() {
        var pricePerUnit = $("#promotional_product_price").val();
        var discountValue = $(this).val();
        if(pricePerUnit != "" && discountValue != "") {
            pricePerUnit = Number(pricePerUnit);
            var discountPercentage = ( Number(discountValue) / pricePerUnit ) * 100;
            $("#promotional_discount_percentage").val(discountPercentage);
        } else {
            $("#promotional_discount_percentage").val('');
        }
    });

    validatePromotionForm("#promotion_form");


    $(document).on('click', '#add_promotional_button', function() {
        event.preventDefault();
        resetForm();
        if($(this).attr('data-product-id') == "") {
            alert("Product needs to be selected against which you need to add promotional product");
            return false;
        }
        $("#promotion_against_id").val($(this).attr('data-product-id'));
        $("#popup-modal-promotional_product #modal-title").text("Add Promotion");
        $("#confirm-btn").removeClass("hidden");
        $("#popup-modal-promotional_product").removeClass('hidden');

    });

    $("#confirm-btn").click(function() {
        $("form#promotion_form").submit();
    });

    $(document).on('click', '#view_promotional_button', function() {
        event.preventDefault();
        resetForm();
        var promotionId = $(this).attr('data-promotion-id');
        $.ajax({
            url: base_url + '/promotions/'+ promotionId,
            method: 'get',
            dataType: 'json',
            success: function(response) {
                if(!$.isEmptyObject(response)) {

                    $("#promotional_product_id").val(response.promotional_product_id);
                    $("#promotional_product_price").val(response.product_on_promotion.price);
                    $("#promotional_discount_percentage").val(response.discount_percentage);
                    $("#promotional_discount_percentage").trigger('keyup');

                    if(response.by_date == 1) {
                        $("#by_date").attr("checked", true);
                        $("#start_date").val(response.start_date);
                        $("#end_date").val(response.end_date);
                        $(".by_date_fields").removeClass("hidden");
                    }

                    if(response.by_no_of_units == 1) {
                        $("#by_no_of_units").attr("checked", true);
                        $("#no_of_units").val(response.no_of_units);
                        $(".by_no_of_units_field").removeClass("hidden");
                    }

                    $("#promotion_description").val(response.description);

                    $("#popup-modal-promotional_product #modal-title").text("View Promotion");
                    $("#confirm-btn").addClass("hidden");
                    $(`
                        <button type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                            id="cancel_promotion_btn"
                            data-promotion-id="${promotionId}"
                        >
                            Cancel Promotion
                        </button>`).insertAfter("#confirm-btn");


                    $("#popup-modal-promotional_product").removeClass('hidden');
                }
            }
        });
    });

    $(document).on('click', '#cancel_promotion_btn', function() {
        if(typeof $(this).attr('data-promotion-id') == 'undefined') {
            return false;
        }

        $.ajax({
            url: base_url + '/promotions/' + $(this).attr('data-promotion-id'),
            method: 'put',
            dataType: 'json',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $("#popup-modal-promotional_product").addClass('hidden');
                if(response.cancelled == 1) {
                    $("#success_message").text("Promotion has been marked as cancelled successfully");
                    $(".alert-success").removeClass('hidden');
                    setTimeout(function() {
                        location.reload();
                    }, 3000);

                } else {
                    $("#error_message").text("Promotion has been marked as cancelled successfully");
                    $(".alert-error").removeClass('hidden');
                }
            }
        });
    });
});



function validatePromotionForm(formObj) {
    $(formObj).validate({
        ignore: [],
        rules: {
            promotional_product_id: {
                required: true,
            },
            promotional_discount_percentage: {
                required: true,
                number: true,
                min: 0
            },
            promotion_description: {
                required: true
            },
            by_date: {
                required: {
                    depends: function (element) {
                        return $('#by_no_of_units').is(":checked") == false;
                    }
                },
            },
            by_no_of_units: {
                required: {
                    depends: function (element) {
                        return $('#by_date').is(":checked") == false;
                    }
                },
            },
            start_date: {
                required: {
                    depends: function(element) {
                        return $('#by_date').is(":checked") == true;
                    }
                },
                dateISO: true,

            },
            end_date: {
                required: {
                    depends: function(element) {
                        return $('#by_date').is(":checked") == true;
                    }
                },
                dateISO: true,

            },
            no_of_units: {
                required: {
                    depends: function(element) {
                        return $('#by_no_of_units').is(":checked") == true;
                    }
                },
                digits: true,
                min: 1

            }
        },
        messages: {
            promotional_product_id: {
                required: "Product Needs to be selected",
            },
            promotional_discount_percentage: {
                required: "Discount needs to be added on the promotional product selected"
            },
            promotion_description: {
                required: "Promotion description needs to be added"
            },
            by_date: {
                required: "Need to add promotion either by date, number of products or both"
            },
            by_no_of_units: {
                required: "Need to add promotion either by date, number of products or both"
            },
            start_date: {
                required: "Promotion start date needs to be entered",
                dateISO: "Enter date in the following format yyyy-mm-dd",
            },
            end_date: {
                required: "Promotion end date needs to be entered",
                dateISO: "Enter date in the following format yyyy-mm-dd",
            },
            no_of_units: {
                required: "Product units needed to be added against which promotion will be offered",
                digits: "Digits only",
                min: "Atleast one product unit needs to be added in promotion"

            }
        },
        errorPlacement: function (error, element) {
            if( $(element).attr('id') == 'purchase_production_interval' || $(element).attr('id') == 'purchase_production_unit') {
                $(error).insertAfter($(element).next());
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

function resetForm() {
    $("#popup-modal-promotional_product form input").each(function(key, element) {

        if($(element).attr('type') == "checkbox") {
            $(element).attr("checked", false);
        } else {
            if($(element).attr('name') != "_token") {
                $(element).val('');
            }
        }

    });

    $(".by_no_of_units_field").addClass('hidden');
    $(".by_date_fields").addClass('hidden');
    $("#cancel_promotion_btn").remove();

    $("label.error").remove();

    $("#popup-modal-promotional_product form textarea").val('');

}
