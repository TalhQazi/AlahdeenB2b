window.$ = window.jQuery = require('jquery');
import 'jquery-datetimepicker';
require('jquery-validation');
require('jquery-validation/dist/additional-methods.js');
require('devbridge-autocomplete');
require('../../../components/promotional_product');
require('../../../components/popup_modal');

$(function() {
    $("#product_id").change(function() {
        $.ajax({
           url: base_url + '/product/get_products?product_id=' + $(this).val() + '&logged_in_user=1',
           method: "get",
           dataType: "json",
           success: function(response) {
               var data = response.suggestions[0].data;
               $("#price_per_unit").val(data.price);

               //check if create blade need to add: add/view promotion button
               if($("#add_product_pricing_promotion").length > 0) {
                    if(data.promotion_offer != null) {
                        if(!$("#add_promotional_button").hasClass('hidden')){
                            $("#add_promotional_button").addClass('hidden')
                        }
                        $("#view_promotional_button").attr('data-promotion-id', data.promotion_offer.id);
                        $("#view_promotional_button").removeClass('hidden');
                    } else {
                        if(!$("#view_promotional_button").hasClass('hidden')){
                            $("#view_promotional_button").addClass('hidden')
                        }
                        $("#add_promotional_button").attr('data-product-id', data.id);
                        $("#add_promotional_button").removeClass('hidden')
                    }
               }
           }
        });
    });

    $("#sales_tax_percentage").keyup(function() {
        var pricePerUnit = $("#price_per_unit").val();
        var salesTaxPercentage = $(this).val();
        if(pricePerUnit != "" && salesTaxPercentage != "") {
            pricePerUnit = Number(pricePerUnit);
            var salesTaxValue = pricePerUnit * ( Number(salesTaxPercentage) / 100 );
            $("#sales_tax_value").val(salesTaxValue);
        } else {
            $("#sales_tax_value").val('');
        }
    });

    $("#sales_tax_value").keyup(function() {
        var pricePerUnit = $("#price_per_unit").val();
        var salesTaxValue = $(this).val();
        if(pricePerUnit != "" && salesTaxValue != "") {
            pricePerUnit = Number(pricePerUnit);
            var salesTaxPercentage = ( Number(salesTaxValue) / pricePerUnit ) * 100;
            $("#sales_tax_percentage").val(salesTaxPercentage);
        } else {
            $("#sales_tax_percentage").val('');
        }
    });

    $("#discount_percentage").keyup(function() {
        var pricePerUnit = $("#price_per_unit").val();
        var discountPercentage = $(this).val();
        if(pricePerUnit != "" && discountPercentage != "") {
            pricePerUnit = Number(pricePerUnit);
            discountPercentage = Number(discountPercentage);
            var discountedValue = pricePerUnit * ( Number(discountPercentage) / 100 );
            $("#discount_value").val(discountedValue);
        } else {
            $("#discount_value").val('');
        }
    });

    $("#discount_value").keyup(function() {
        var pricePerUnit = $("#price_per_unit").val();
        var discountValue = $(this).val();
        if(pricePerUnit != "" && discountValue != "") {
            pricePerUnit = Number(pricePerUnit);
            discountValue = Number(discountValue);
            var discountedPercentage =  ( discountValue / pricePerUnit ) * 100;
            $("#discount_percentage").val(discountedPercentage);
        } else {
            $("#discount_percentage").val('');
        }
    });

    validatePricingForm("#product_pricing_form");

    if($("#sales_tax_percentage").val() != "") {
        $("#sales_tax_percentage").keyup();
    }

    if($("#discount_percentage").val() != "") {
        $("#discount_percentage").keyup();
    }

});


function validatePricingForm(formObj) {
    $(formObj).validate({
        ignore: [],
        rules: {
            product_id: {
                required: true,
            },
            total_units: {
                required: true,
                number: true,
            },
            price_per_unit: {
                required: true,
                number: true,
            },
            avg_cost_per_unit: {
                required: true,
                number: true,
            },
            sales_tax_percentage: {
                number: true,
                min: 0,
            },
            discount_percentage: {
                number: true,
                min: 0,
            },
        },
        messages: {
            product_id: {
                required: "Product Needs to be selected",
            },
            total_units: {
                required: "Total number of units are required"
            },
            price_per_unit: {
                required: "Price per unit is required"
            },
            avg_cost_per_unit: {
                required: "Average cost per unit is required",
            },
            sales_tax_percentage: {
                min: "Sales Tax Percentage can not be less then 0",
            },
            discount_percentage: {
                min: "Discount Percentage can not be less then 0",
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
