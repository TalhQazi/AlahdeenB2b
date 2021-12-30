window.$ = window.jQuery = require('jquery');
require('jquery-validation');
require('devbridge-autocomplete');

$(document).ready(function() {

    $("#title").autocomplete({
        minChars: 3,
        serviceUrl: base_url + '/admin/category/get-categories',
        dataType: 'json',
        onSelect: function (suggestion) {
            $("#cat_id").val(suggestion.data.id);
        }
    });

    validateCategoryForm('#category_create_form');
});


function validateCategoryForm(formObj) {

    $(formObj).validate({
        rules: {
            title: {
                required: true,
                minlength: 3,
                maxlength: 100,
            },
            display_section: {
                required: true,
            },
            display_order: {
                required: true,
                min: 1
            }
        },
        // messages: {
        //     title: {
        //         required: "Please enter category title",
        //         minlength: "Category title should be atleast 3 characters long",
        //         maxlength: "Category title should not exceed 100 characters",

        //     },
        // },
        errorPlacement: function (error, element) {
            $(error).insertAfter(element);
        },
        submitHandler: function(form) {
            // do other things for a valid form
            form.submit();
        }
    });
}
