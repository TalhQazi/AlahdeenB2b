window.$ = window.jQuery = require('jquery');
require('jquery-validation');

$(document).ready(function() {

    $("#image_path").change(function() {
        readURL(this);
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
        },
        messages: {
            title: {
                required: "Please enter category title",
                minlength: "Category title should be atleast 3 characters long",
                maxlength: "Category title should not exceed 100 characters",

            },
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
