const { ajax } = window.$ = window.jQuery = require('jquery');
require('devbridge-autocomplete');

$(document).ready(function() {
    $("#product").autocomplete({
        minChars: 3,
        serviceUrl: base_url + '/product/get_products',
        dataType: 'json',
        preserveInput: false,
        params: {
            logged_in_user: true
        },
        onSelect: function (suggestion) {

        }
    });
});

function validateQuotationForm(formObj) {

    $(formObj).validate({
        rules: {
            product: {
                required: true,
            },
            quantity: {
                required: true,
                number: true,
            },
            unit: {
                required: true,
            },
            price: {
                required: true,
                number: true
            }

        },
        messages: {
            product: {
                required: 'Please select one of your products',
            },
            quantity: {
                required: 'Please enter quantity',
                number: 'Qunatity can only consists of number or decimal'
            },
            unit: {
                required: 'Please select unit',
            },
            quantity: {
                required: 'Please enter price',
                number: 'Price can only consists of number or decimal'
            },
        },
        submitHandler: function(form) {
            // do other things for a valid form
            form.submit();
        }
    });
}
