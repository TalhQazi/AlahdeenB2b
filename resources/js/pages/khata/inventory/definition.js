window.$ = window.jQuery = require('jquery');
require('jquery-validation');
require('jquery-validation/dist/additional-methods.js');
require('devbridge-autocomplete');



$(function () {
    $("#product_name").autocomplete({
        minChars: 3,
        serviceUrl: base_url + '/product/get_products',
        dataType: 'json',
        preserveInput: false,
        params: {
          logged_in_user: true
        },
        onSelect: function (suggestion) {

          if(suggestion.data.description != null) {
              $("#description").text(suggestion.data.description.replace(/(<([^>]+)>)/gi, ""));
          }

          $("#product_id").val(suggestion.data.id);


          var breadCrumb = suggestion.data.category.bread_crumb.split(';');
          breadCrumb.splice(0, 1);
          breadCrumb.splice(breadCrumb.length-1, 1);

          var index = 0;
          $(breadCrumb).each(function(key, category_id) {
                index = key + 1;
                //Level 1 categories drop down already exists, therefore setting its value and creating sub level drop down
                if(index == 1) {
                    $(`#category_level_${index}`).val(category_id);
                    createCategoryDropDown(index);
                } else { //Setting value for level 2 and below, and creating sub level drop down for one level below the current level
                    setValue(key, category_id, breadCrumb[key-1])
                    createCategoryDropDown(index);
                }
          });

          //setting value of the actual category of product in its desired level
          setValue(index, suggestion.data.category.id, breadCrumb[breadCrumb.length-1]);

          if (suggestion.data.images != null) {
            $("#upload_image_label").remove();
            var imagesCount = Object.keys(suggestion.data.images.data).length;
            var imgIsSet = false;
            for (var index = 0; index < imagesCount; index++) {

              if (suggestion.data.images.data[index].is_main == "1") {
                var imgUrl = suggestion.data.images.data[index].path;
                $(`#product_image_preview`).attr('src', base_url + imgUrl);
                $(`#product_image_preview`).css('width', '160px');
                $(`#product_image_preview`).css('height', '160px');
                $(`#product_image_path`).val(suggestion.data.images.data[index].path);

                imgIsSet = true;
              }
            }

            if (!imgIsSet) {
              var imgUrl = suggestion.data.images.data[0].path;
              $(`#product_image_preview`).attr('src', base_url + imgUrl);
                $(`#product_image_preview`).css('width', '160px');
                $(`#product_image_preview`).css('height', '160px');
                $(`#product_image_path`).val(suggestion.data.images.data[index].path);
            }
          }
        }

    });

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
                              <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'>Select Sub Category</label>
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

    $("#product_image").change(function() {
        readURL(this);
    });

    validateDefinitionForm("#product_definition_form");

    if($("#product_name").val() != "") {
        $("#product_name").autocomplete('onValueChange');
    }
});

function createCategoryDropDown(level) {
    var nextLevel = Number(level) + 1;
    var html = ` <div class='w-full md:w-full px-3 mb-6' id="level_${nextLevel}_div">
                              <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'>Select Sub Category</label>
                              <div class="flex-shrink w-full inline-block relative">
                                  <select class="block appearance-none text-gray-600 w-full bg-white border border-gray-400 shadow-inner px-4 py-2 pr-8 rounded category" data-level="${nextLevel}" name="category[${nextLevel}]" id="category_level_${nextLevel}">
                                  </select>
                                  <div class="pointer-events-none absolute top-0 mt-3  right-0 flex items-center px-2 text-gray-600">
                                      <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                                  </div>
                              </div>
                          </div>`;
    $(html).insertAfter(`#level_${level}_div`);
}

function setValue(level, value, parent_cat_id) {

    level = Number(level) + 1;
    $.ajax({
        url: base_url + '/categories/get-categories?level=' + level + '&parent_cat_id=' + parent_cat_id,
        method: 'get',
        dataType: 'json',
        success: function(response) {
          if(response.suggestions.length > 0) {
            var selectHtml = '<option value="">Select Sub Category</option>';
            $(response.suggestions).each(function(key, suggestion) {
              selectHtml += `<option value="${suggestion.data.id}">${suggestion.value}</option>`;
            });

            $(`#category_level_${level}`).html(selectHtml);
            $(`#category_level_${level}`).val(value);

          }
        }
      });
}

function validateDefinitionForm(formObj) {
    $(formObj).validate({
        ignore: [],
        rules: {
            product_image: {
                extension: "jpg|jpeg|png",
                maxsize: 500000,
            },
            product_code: {
                required: true,
            },
            product_name: {
                required: true,
            },
            'category[]': {
                required: true,
            },
            purchase_unit: {
                required: true,
            },
            conversion_factor: {
               required: true,
               min: 1,
            },
            purchase_production_interval: {
                required: true,
                min: 1,
            },
            purchase_production_unit: {
                required: true,
            }
        },
        messages: {
            product_image: {
                extension: "Only jpg, jpeg or png format can be uploaded",
                maxsize: "Photo size can not exceed 500Kb"
            },
            product_code: {
                required: "Product code is required"
            },
            product_name: {
                required: "Product name is required",
            },
            'category[]': {
                required: "Category is required",
            },
            purchase_unit: {
                required: "Purchase unit is required"
            },
            conversion_factor: {
                required: "Product conversion factor is required",
                min: "Product conversion factor should at least be equal to 1"
            },
            purchase_production_interval: {
                required: "Purchase/Production interval is required",
                min: "Purchase/Production should at least be equal to 1"
            },
            purchase_production_unit: {
                required: "Purchase/Production unit is required",
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
