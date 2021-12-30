window.$ = window.jQuery = require('jquery');
require('jquery-validation');
require('jquery-validation/dist/additional-methods.js');
require('devbridge-autocomplete');

$(document).ready(function() {

    $("#bulk_category").keypress(function(){
        var searchLevel = (Number) ($(this).attr('data-level'));
        var currentLvlSelected = (searchLevel == 1) ? searchLevel : searchLevel - 1;
        var parentCatId = $("#category_level_"+currentLvlSelected).length > 0 ? $("#category_level_"+currentLvlSelected).attr('data-cat-id') : '';

        var url = base_url + '/categories/get-categories?level=' + searchLevel;
        if(parentCatId != '') {
            url += '&parent_cat_id=' + parentCatId;
        }


        $(this).autocomplete({
            minChars: 3,
            serviceUrl: url,
            dataType: 'json',
            preserveInput: true,
            showNoSuggestionNotice: true,
            noSuggestionNotice: "No Results",
            onSelect: function (suggestion) {

                var suggestionSpanId = 'bulk_category_level_'+searchLevel;
                var inputId = $(this).attr('data-target-div');

                if( $("#"+suggestionSpanId).length < 1) {

                    var selectedProductHtml = '';

                    if(searchLevel >= 2) {
                        selectedProductHtml += `<span class="mx-1" data-level="${searchLevel}">></span>`;
                    }

                    selectedProductHtml +=   `<span id="${suggestionSpanId}" class="inline-block rounded-full text-gray-600 bg-green-200 px-4 py-2 text-xs font-bold" data-level="${searchLevel}" data-cat-id="${suggestion.data.id}">
                                                    ${suggestion.value}
                                                    <i class="fa fa-times ml-2 delete-bulk-category"></i>
                                                    <input type='hidden' name="bulk_categories[${searchLevel}]" value="${suggestion.data.id}">
                                                    <input type='hidden' name="bulk_categories_name[${searchLevel}]" value="${suggestion.value}">
                                                </span>`;

                    $(inputId).append(selectedProductHtml);


                    $(this).attr('data-level', searchLevel + 1);
                    $(this).val('');

                    if(searchLevel == 2) {
                        alert('Great now you can select further sub categories if any or you can add the product against the selected category and sub category')
                    } else if(searchLevel == 1) {
                        alert('Great now you need to select a sub category')
                    }
                }


            }
        });
    });

    $("#category").keypress(function(){
        var searchLevel = (Number) ($(this).attr('data-level'));
        var currentLvlSelected = (searchLevel == 1) ? searchLevel : searchLevel - 1;
        var parentCatId = $("#category_level_"+currentLvlSelected).length > 0 ? $("#category_level_"+currentLvlSelected).attr('data-cat-id') : '';

        var url = base_url + '/categories/get-categories?level=' + searchLevel;
        if(parentCatId != '') {
            url += '&parent_cat_id=' + parentCatId;
        }


        $(this).autocomplete({
            minChars: 3,
            serviceUrl: url,
            dataType: 'json',
            preserveInput: true,
            showNoSuggestionNotice: true,
            noSuggestionNotice: "No Results",
            onSelect: function (suggestion) {

                var suggestionSpanId = 'category_level_'+searchLevel;
                var inputId = $(this).attr('data-target-div');

                if( $("#"+suggestionSpanId).length < 1) {

                    var selectedProductHtml = '';

                    if(searchLevel >= 2) {
                        selectedProductHtml += `<span class="mx-1" data-level="${searchLevel}">></span>`;
                    }

                    selectedProductHtml +=   `<span id="${suggestionSpanId}" class="inline-block rounded-full text-gray-600 bg-green-200 px-4 py-2 text-xs font-bold" data-level="${searchLevel}" data-cat-id="${suggestion.data.id}">
                                                    ${suggestion.value}
                                                    <i class="fa fa-times ml-2 delete-category"></i>
                                                    <input type="hidden" name="categories[${searchLevel}]" value="${suggestion.data.id}">
                                                    <input type="hidden" name="categories_name[${searchLevel}]" value="${suggestion.value}">
                                                </span>`;

                    $(inputId).append(selectedProductHtml);


                    $(this).attr('data-level', searchLevel + 1);
                    $(this).val('');

                    if(searchLevel == 2) {
                        alert('Great now you can select further sub categories if any or you can add the product against the selected category and sub category')
                    } else if(searchLevel == 1) {
                        alert('Great now you need to select a sub category')
                    }
                }


            }
        });
    });

    $(document).on("click", ".delete-category", function() {
        var level = $(this).parent('span').attr('data-level');
        $("#categories_div span").each(function(key, element) {
            if((Number)($(element).attr('data-level')) >= level) {

                $(this).remove();
            }
        });

        var levelSelected = $("#categories_div span").length > 0 ? $("#categories_div span").length + 1 : 1;
        $("#category").attr('data-level',levelSelected);
    });

    $(document).on("click", ".delete-bulk-category", function() {
        var level = $(this).parent('span').attr('data-level');
        $("#bulk_categories_div span").each(function(key, element) {
            if((Number)($(element).attr('data-level')) >= level) {

                $(this).remove();
            }
        });

        var levelSelected = $("#bulk_categories_div span").length > 0 ? $("#bulk_categories_div span").length + 1 : 1;
        $("#bulk_category").attr('data-level',levelSelected);
    });

    $("#logo").change(function() {
        readURL(this);
    });

    $('#unit_measure_quantity').keyup(function () {
        $('#unit_measure_supply').val($(this).val());
    });

    $('#unit_measure_supply').keyup(function () {
        $('#unit_measure_quantity').val($(this).val());
    });

    validateSingleProductForm("#matter_sheet_product_form");
});

function readURL(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      var inputId = $(input).attr('id');
      var photoNo = inputId.replace('photo_','');

      reader.onload = function(e) {
        $('#' + inputId + '_preview').attr('src', e.target.result);
        if(!$('#' + inputId + '_preview').hasClass('h-40')) {
            $('#' + inputId + '_preview').addClass('h-40');
        }

        $('#company_photo_id_' + photoNo).remove();
      }

      reader.readAsDataURL(input.files[0]); // convert to base64 string
    }
}

function validateSingleProductForm(formObj) {
    $(formObj).validate({
        ignore: [],
        rules: {
            logo: {
                extension: "jpg|jpeg|png",
                maxsize: 200000,
            },
            title: {
                required: true,
                minlength: 3,
            },
            price: {
                required: true,
                number: true,
                min: 1,
            },
        },
        messages: {
            logo: {
                extension: "Only following types: jpg, jpeg, png are supported",
                maxsize: "Max image size can be 2 Mb",
            },
            title: {
                required: "Product Title is required",
                minlength: "Product Title character length needs to be greater or equal to 3",
            },
            price: {
                required: "Product Price is required",
                number: "Product Price can only consist of digits or decimal",
                min: "Product Price needs to be greater or equal to 1"
            },
        },
        errorPlacement: function (error, element) {
            if ($(element).attr('name') == 'phone_number') {
                $(error).insertAfter($(element).parent());
            } else {
                $(error).insertAfter(element);
            }
        },
        submitHandler: function(form) {
            // do other things for a valid form

            // $(formObj + ' input:disabled').each(function(e) {
            //     $(this).removeAttr('disabled');
            // });
            form.submit();
        }
    });
}


