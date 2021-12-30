window.$ = window.jQuery = require('jquery');
require('jquery-validation');
require('jquery-validation/dist/additional-methods.js');
require('devbridge-autocomplete');

$(document).ready(function() {
    $("#product_name").autocomplete({
        minChars: 3,
        serviceUrl: base_url + '/product/get_products',
        dataType: 'json',
        preserveInput: false,
        onSelect: function (suggestion) {
            $(this).val(suggestion.value);
            $("#product_id").val(suggestion.data.id)
        }
    });
})
