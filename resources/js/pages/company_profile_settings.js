window.$ = window.jQuery = require('jquery');
require('jquery-validation');
require('jquery-validation/dist/additional-methods.js');
require('devbridge-autocomplete');

$(document).ready(function() {

    $(document).on('focus', '.product_autocomplete', function () {
        $(this).autocomplete({
          minChars: 3,
          serviceUrl: base_url + '/product/get_products',
          dataType: 'json',
          preserveInput: false,
          params: {
            logged_in_user: true
          },
          onSelect: function (suggestion) {

            var fieldId = $(this).attr('id');

            if(fieldId.indexOf('banner') != -1) {
                var fieldNo = $(this).attr('id').replace('banner_products_name_', '');
                var imagePreviewId = `#banner_products_${fieldNo}_preview`;
                $(`#banner_products_${fieldNo}`).val(suggestion.data.id);
            } else {
                var fieldNo = $(this).attr('id').replace('top_products_name_', '');
                var imagePreviewId = `#top_products_${fieldNo}_preview`;
                $(`#top_products_${fieldNo}`).val(suggestion.data.id);
            }

            if (suggestion.data.images != null) {
              var imagesCount = Object.keys(suggestion.data.images.data).length;
              var imgIsSet = false;
              for (var index = 0; index < imagesCount; index++) {
                if (suggestion.data.images.data[index].is_main == "1") {
                  var imgUrl = base_url + suggestion.data.images.data[index].path;
                  $(imagePreviewId).attr('src', imgUrl);
                  $(imagePreviewId).css('width', '96px');
                  $(imagePreviewId).css('height', '96px');
                  $(imagePreviewId).next('.remove_image').removeClass('hidden');

                  imgIsSet = true;
                }
              }

              if (!imgIsSet) {
                var imgUrl = base_url + suggestion.data.images.data[0].path;
                $(imagePreviewId).attr('src', imgUrl);
                $(imagePreviewId).css('width', '96px');
                $(imagePreviewId).css('height', '96px');
                $(imagePreviewId).next('.remove_image').removeClass('hidden');
              }
            }
          }


        });
    });

    $("#company_banner").change(function() {
        readURL(this);
    });
});


function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        var inputId = $(input).attr('id');

        if(inputId.indexOf('q_image') != -1) {
            var fieldNo = inputId.replace('q_image_', '');
            $(`#q_image_path_${fieldNo}`).val('');
        }

        reader.onload = function(e) {
            if(input.files[0].type.indexOf('image') != -1) {
                $('#' + inputId + '_preview').attr('src', e.target.result);
            } else {
                $('#' + inputId + '_preview').attr('src', base_url + '/img/camera_icon.png');
            }

            if(!$('#' + inputId + '_preview').hasClass('h-40')) {
                $('#' + inputId + '_preview').addClass('h-40');
            }

        }


      reader.readAsDataURL(input.files[0]); // convert to base64 string
    }
}
