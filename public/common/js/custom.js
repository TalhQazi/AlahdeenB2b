// Custom Jquery

function onChangeMainCategory()
{
	// $(document).on("")
}

function test()
{
	alert("Test Laravel");
}

function startSelect2()
{
	$(".select2").select2();
}

function onChangeMainCategorySelect2($url, $target)
{
    $(document).on('select2:select', '.main_category_select2', function (e) {
        $cat_id = $(this).val();
        $new_url = $url + "/" + $cat_id;
        $.get($new_url, function(response){
            if(response.error == '')
            {
                // $($target).show();
                $($target).html(response.html);
                startSelect2();
            }else
            {
            	alert("Something Went Wrong");
            }
            
        });

        $new_url = $url;
    });
}


function onClickDriverRegisterNavLink()
{
    return true;
    $(document).on("click", ".driver_register_nav_link", function(){
        $target = $(this).attr("data-href");
        location.href = $target;
    });
}








function mapDataToFields(data)
{
    $.map(data, function(value, index){
        var input = $('[name="'+index+'"]');
        if($(input).length && $(input).attr('type') !== 'file')
        {
          if(($(input).attr('type') == 'radio' || $(input).attr('type') == 'checkbox') && value == $(input).val())
            $(input).prop('checked', true);
          else
              $(input).val(value).change();
        }
    });
}






function startDriverImageDropify()
{
    $('.driver_image_dropify').dropify({
        messages: {
            'default': 'Click to upload image',
            'replace': 'Click to upload image to replace',
        }
    });
}


function startLicensePhotoDropify()
{
    $('.license_photo_dropify').dropify({
        messages: {
            'default': 'Click to upload license image',
            'replace': 'Click to upload license image to replace',
        }
    });
}


function startIdPhotoDropify()
{
    $('.id_image_dropify').dropify({
        messages: {
            'default': 'Click to upload id confirmation image',
            'replace': 'Click to upload id confirmation image to replace',
        }
    });
}


function startFrontSideImageDropify()
{
    $('.front_side_image_dropify').dropify({
        messages: {
            'default': 'Click to upload front side image of cnic',
            'replace': 'Click to upload front side image of cnic to replace',
        }
    });
}

function startBackSideImageDropify()
{
    $('.back_side_image_dropify').dropify({
        messages: {
            'default': 'Click to upload back side image of cnic',
            'replace': 'Click to upload back side image of cnic to replace',
        }
    });
}

// sdf
function startVehicleImageDropify()
{
    $('.photo_of_vehicle_dropify').dropify({
        messages: {
            'default': 'Click to upload vehicle image',
            'replace': 'Click to upload vehicle image to replace',
        }
    });
}

function startVehicleRegDocumentDropify()
{
    $('.vehicle_reg_certificate_dropify').dropify({
        messages: {
            'default': 'Click to upload vehicle registration certificate',
            'replace': 'Click to upload vehicle registration certificate to replace',
        }
    });
}

function startVehicleFitnessDocumentDropify()
{
    $('.vehicle_fitness_certificate_dropify').dropify({
        messages: {
            'default': 'Click to upload vehicle fitness certificate',
            'replace': 'Click to upload vehicle fitness certificate to replace',
        }
    });
}



function validateDriverAboutInfoFormFirstTime()
{
    $(".driver_about_info_form").validate({
        rules: {
            about_image: {
                required: true,
            },
            first_name: {
                required: true,
            },
            last_name: {
                required: true,
            },
            about_date_of_birth: {
                required: true,
            },
            email: {
                required: true,
                email: true,
            },
            password: {
                required: true,
            },
            password_confirmation: {
                required: true,
                equalTo: "#user-password"
            },
        },
        messages: {
            about_image: {
                required: 'Upload image',
            },
            first_name: {
                required: 'Enter first name',
            },
            last_name: {
                required: 'Enter last name',
                regex: 'Name must have at least two letters',
            },
            about_date_of_birth: {
                required: 'Enter date of birth',
            },
            email: {
                required: 'Enter email',
            },
            password: {
                required: "Enter password",
            },
            password_confirmation: {
                required: "Re-enter your password",
                equalTo: "Confirm your password"
            },
        },
        errorPlacement: function(error, element) {
             $(element).addClass('is-invalid');
             $(element).closest('.form-group').find('.invalid-feedback').html('<strong>'+error.html()+'</strong>');
             $(element).closest('.my_form_group').find('.invalid-feedback').html('<strong>'+error.html()+'</strong>');
        },
        // set this class to error-labels to indicate valid fields
        success: function(label) {
            // set &nbsp; as text for IE
            label.html("&nbsp;").addClass("checked");
        },
        highlight: function(element, errorClass) {
            $(element).parent().next().find("." + errorClass).removeClass("checked");
        },
        unhighlight: function (element) {
            $(element).removeClass('is-invalid');
            $(element).closest('.form-group').find('.invalid-feedback').html('');
            $(element).closest('.my_form_group').find('.invalid-feedback').html('');
        }
    });

}


function validateDriverLicenseInfoFormFirstTime()
{
    $(".driver_license_info_form").validate({
        rules: {
            license_image: {
                required: true,
            },
            license_number: {
                required: true,
            },
            license_date_of_expiration: {
                required: true,
            },
        },
        messages: {
            license_image: {
                required: 'Upload license image',
            },
            license_number: {
                required: 'Enter license number',
            },
            license_date_of_expiration: {
                required: 'Enter date of expiration',
            },
        },
        errorPlacement: function(error, element) {
             $(element).addClass('is-invalid');
             $(element).closest('.form-group').find('.invalid-feedback').html('<strong>'+error.html()+'</strong>');
             $(element).closest('.my_form_group').find('.invalid-feedback').html('<strong>'+error.html()+'</strong>');
        },
        // set this class to error-labels to indicate valid fields
        success: function(label) {
            // set &nbsp; as text for IE
            label.html("&nbsp;").addClass("checked");
        },
        highlight: function(element, errorClass) {
            $(element).parent().next().find("." + errorClass).removeClass("checked");
        },
        unhighlight: function (element) {
            $(element).removeClass('is-invalid');
            $(element).closest('.form-group').find('.invalid-feedback').html('');
            $(element).closest('.my_form_group').find('.invalid-feedback').html('');
        }
    });

}


function validateDriverCnicInfoFormFirstTime()
{
    $(".driver_cnic_info_form").validate({
        rules: {
            license_image: {
                required: true,
            },
            license_number: {
                required: true,
            },
            license_date_of_expiration: {
                required: true,
            },
        },
        messages: {
            license_image: {
                required: 'Upload license image',
            },
            license_number: {
                required: 'Enter license number',
            },
            license_date_of_expiration: {
                required: 'Enter date of expiration',
            },
        },
        errorPlacement: function(error, element) {
             $(element).addClass('is-invalid');
             $(element).closest('.form-group').find('.invalid-feedback').html('<strong>'+error.html()+'</strong>');
             $(element).closest('.my_form_group').find('.invalid-feedback').html('<strong>'+error.html()+'</strong>');
        },
        // set this class to error-labels to indicate valid fields
        success: function(label) {
            // set &nbsp; as text for IE
            label.html("&nbsp;").addClass("checked");
        },
        highlight: function(element, errorClass) {
            $(element).parent().next().find("." + errorClass).removeClass("checked");
        },
        unhighlight: function (element) {
            $(element).removeClass('is-invalid');
            $(element).closest('.form-group').find('.invalid-feedback').html('');
            $(element).closest('.my_form_group').find('.invalid-feedback').html('');
        }
    });

}




function validateDriverIdInfoFormFirstTime()
{
    $(".driver_id_confirmation_info_form").validate({
        rules: {
            id_image: {
                required: true,
            },
            id_name: {
                required: true,
            },
        },
        messages: {
            id_image: {
                required: 'Select image',
            },
            id_name: {
                required: 'Enter name',
            },
        },
        errorPlacement: function(error, element) {
             $(element).addClass('is-invalid');
             $(element).closest('.form-group').find('.invalid-feedback').html('<strong>'+error.html()+'</strong>');
             $(element).closest('.my_form_group').find('.invalid-feedback').html('<strong>'+error.html()+'</strong>');
        },
        // set this class to error-labels to indicate valid fields
        success: function(label) {
            // set &nbsp; as text for IE
            label.html("&nbsp;").addClass("checked");
        },
        highlight: function(element, errorClass) {
            $(element).parent().next().find("." + errorClass).removeClass("checked");
        },
        unhighlight: function (element) {
            $(element).removeClass('is-invalid');
            $(element).closest('.form-group').find('.invalid-feedback').html('');
            $(element).closest('.my_form_group').find('.invalid-feedback').html('');
        }
    });

}













function validateDriverVehicleInfoFormFirstTime()
{
    $(".driver_vehicle_info_form").validate({
        rules: {
            vehicle_company_name: {
                required: true,
            },
            vehicle_number_plate: {
                required: true,
            },
            vehicle_image: {
                required: true,
            },
            vehicle_reg_certificate: {
                required: true,
            },
            vehicle_fitness_certificate: {
                required: true,
            }
        },
        messages: {
            vehicle_company_name: {
                required: 'Enter company name',
            },
            vehicle_number_plate: {
                required: 'Enter number plate',
            },
            vehicle_image: {
                required: "Upload image of vehicle",
            },
            vehicle_reg_certificate: {
                required: "Upload vehicle registration certificate",
            },
            vehicle_fitness_certificate: {
                required: "Upload vehicle fitness certificate",
            }
        },
        errorPlacement: function(error, element) {
             $(element).addClass('is-invalid');
             $(element).closest('.form-group').find('.invalid-feedback').html('<strong>'+error.html()+'</strong>');
             $(element).closest('.my_form_group').find('.invalid-feedback').html('<strong>'+error.html()+'</strong>');
        },
        // set this class to error-labels to indicate valid fields
        success: function(label) {
            // set &nbsp; as text for IE
            label.html("&nbsp;").addClass("checked");
        },
        highlight: function(element, errorClass) {
            $(element).parent().next().find("." + errorClass).removeClass("checked");
        },
        unhighlight: function (element) {
            $(element).removeClass('is-invalid');
            $(element).closest('.form-group').find('.invalid-feedback').html('');
            $(element).closest('.my_form_group').find('.invalid-feedback').html('');
        }
    });
    
}




function startDatePicker()
{
    $(function() {
        $( ".datepicker" ).datetimepicker({
            format: "Y-mm-d"
        }).val();
    });
}