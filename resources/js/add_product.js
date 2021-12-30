const { get } = require('jquery');

window.$ = window.jQuery = require('jquery');
require('jquery-validation');



var addProductDetailsBtn = document.getElementById('add_product_details');
var keyHtml = "<label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='grid-text-1'>Product Detail Key <i class='fas fa-asterisk text-red-500'></i></label>"+
              "<input class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500' name='product_details_key[]' id='product_details_key_1' type='text' required>";
var valueHtml = "<label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='grid-text-1'>Product Detail Value <i class='fas fa-asterisk text-red-500'></i></label>"+
                "<input class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500' name='product_details_value[]' id='product_details_key_1' type='text' required>";
addProductDetailsBtn.onclick = function(event) {
    // console.log(document.getElementById('add_product_details').previousSibling.parentElement);
    var keyDiv = document.createElement('div');
    var valueDiv = document.createElement('div');
    keyDiv.classList.add("w-full","md:w-full","px-3","mb-6");
    valueDiv.classList.add("w-full","md:w-full","px-3","mb-6");
    keyDiv.innerHTML = keyHtml;
    valueDiv.innerHTML = valueHtml;
    var productSpecDiv = document.getElementById('prod_specs_div');
    console.log(productSpecDiv.lastChild);
    productSpecDiv.insertBefore(keyDiv,productSpecDiv.lastChild);
    productSpecDiv.insertBefore(valueDiv,productSpecDiv.lastChild);
    // document.getElementById('add_product_details').parentElement.lastElementChild.appendChild(keyDiv);
    // document.getElementById('add_product_details').parentElement.lastElementChild.appendChild(valueDiv);
    // console.log(uplaodImageDiv);
    // uplaodImageDiv.appendChild(fileInput);
}

$(function () {
    validateProductForm('#add_product_form');

    $(document).on('change', '.category', function() {
        var level = (Number) ($(this).attr('data-level'));
        var searchLevel = level + 1;

        //Removing dropdowns for all the level below the one user changed against
        $('.category').each(function(key,element) {
            if( $(element).attr('data-level') > level ) {
                var elementLevel = $(element).attr('data-level');
                $(`#level_${elementLevel}_div`).remove();
            }
        });


        $.ajax({
          url: base_url + '/categories/get-categories?level=' + searchLevel + '&parent_cat_id=' + $(this).val(),
          method: 'get',
          dataType: 'json',
          success: function(response) {
            if(response.suggestions.length > 0) {
              var selectHtml = '<option value="">Select Sub Category</option>';
              $(response.suggestions).each(function(key, suggestion) {
                selectHtml += `<option value="${suggestion.data.id}">${suggestion.value}</option>`;
              });
              var html = ` <div class='w-full md:w-full px-3 mb-6' id="level_${searchLevel}_div">
                              <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'>Select Sub Category<i class="fas fa-asterisk text-red-500"></i></label>
                              <div class="flex-shrink w-full inline-block relative">
                                  <select class="block appearance-none text-gray-600 w-full bg-white border border-gray-400 shadow-inner px-4 py-2 pr-8 rounded category" data-level="${searchLevel}" name="category[${searchLevel}]" id="category_level_${searchLevel}">
                                     ${selectHtml}
                                  </select>
                                  <div class="pointer-events-none absolute top-0 mt-3  right-0 flex items-center px-2 text-gray-600">
                                      <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                                  </div>
                              </div>
                          </div>`;
              $(html).insertAfter(`#level_${level}_div`);
            }
          }
        });
    });

    if($("#category_level_1").val() != '') {
        $('.category').trigger('change');
    }
});

function validateProductForm(formObj) {
    $(formObj).validate({
        rules: {
            title: {
                required: true,
                minlength: 3,
                maxlength: 255,
            },
            description: {
                required: true,
            },
            price: {
                required: true,
                number: true,
            },
            'category[]': {
              required: true,
            },
            'product_details_key[]': {
                required: true,
            },
            'product_details_value[]': {
                required: true,
            },
        },
        messages: {
            title: {
                required: "Product title is required",
                minlength: "Please enter at least 3 characters.",
                maxlength: "Please enter not more than 25 characters."
            },
            description: {
                required: "Product Description is required",
            },
            price: {
                required: "Product Price number is required",
                number: "Price can only consist of numbers or be in decimal form"
            },
            'category[]': {
              required: "Need to select main categorycategory",
            },
            'product_details_key[]': {
                required: "Product specification is required",
            },
            'product_details_value[]': {
                required: "Product specification is required",
            },
        }
    });
}





