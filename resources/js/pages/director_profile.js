window.$ = window.jQuery = require('jquery');
require('jquery-validation');
require('jquery-validation/dist/additional-methods.js');
require('../components/validation_methods.js');

$(document).ready(function() {

    $("#director_photo").change(function() {
        readURL(this);
    });

    validateDirectorProfileForm('#director_profile_form');

    tinymce.init({
        selector: '#description'
    });

    $(document).on("click", ".edit_director", function() {
        var row = $(this).parents('tr');
        var formUrl = $(this).attr('data-url');
        var formMethod = '<input type="hidden" name="_method" value="PUT"/>';

        $(row).children().each(function(key, element) {

            if(key == 1) {
                $("#director_profile_form #director_photo_preview").attr('src', $(element).children('img').attr('src'));
            } else if(key == 2) {
                $("#director_profile_form #name").val($(element).text());
            } else if(key == 3) {
                $("#director_profile_form #designation").val($(element).text());
            } else if(key == 4) {
                tinymce.activeEditor.setContent($(element).html());
            }
        });

        $("#director_profile_form").attr('action', formUrl);
        if($("#director_profile_form input[name='_method']").length == 0) {
            $("#director_profile_form").prepend(formMethod);
        }

        $("#business-director-modal").removeClass('hidden');
    });

    $(".add_director").click(function(event) {
        event.preventDefault();
        if($("#director_profile_form input[name='_method']").length > 0) {
            $("#director_profile_form input[name='_method']").remove();
        }
        $("#director_profile_form input").each(function(key, element) {
            if($(element).attr('name') != "_token") {
                $(element).val('');
            }
        });

        $("#director_photo_preview").attr('src', base_url + '/img/camera_icon.png');
        tinymce.activeEditor.setContent('');

        $("#business-director-modal").removeClass('hidden');
    });

    $(".close-modal").click(function() {
        $(".modal").each(function(index,element){
            console.log(element);
            if(!$(element).hasClass('hidden')) {
                $(element).addClass('hidden')
            }
        });
    });

    $('.remove_director').on('click', function (e) {
        e.preventDefault();
        let $this = $(this);
        if (confirm('Are you sure you want to remove this director?')) {
            $this.parents('form').submit();
        } else {
            return false;
        }
    });
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


function validateDirectorProfileForm(formObj) {

    $(formObj).validate({
        ignore: [],
        rules: {
            director_photo: {
                extension: "jpg|jpeg|png",
                maxsize: 500000,
            },
            name: {
                required: true,
                lettersonly: true,
            },
            designation: {
                required: true,
            },
        },
        messages: {
            director_photo: {
                extension: "Only jpg, jpeg or png format can be uploaded",
                maxsize: "Photo size can not exceed 500Kb"
            },
            name: {
                required: "Name is required",
                lettersonly: "Name can only consist of letters"
            },
            designation: {
                required: "Designation is required",
            },
        },
        errorPlacement: function (error, element) {
            $(error).insertAfter(element);
        },
        submitHandler: function(form) {
            // do other things for a valid form
            form.submit();
        }
    });
}




