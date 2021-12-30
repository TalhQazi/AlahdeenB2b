window.$ = window.jQuery = require('jquery');
require('jquery-validation');
require('devbridge-autocomplete');

$(document).ready(function() {

    $("#search_keywords").keyup(function() {
      var url = base_url + '/admin/testimonials';

      if( $(this).val() == "" ) {
          window.location.href = url;
      } else {
          ajaxSearch(url, true, ".search_results");
      }

    });

    $('body').on('click', '#pagination a', function(e) {
      e.preventDefault();

      $('#load a').css('color', '#dfecf6');
      $('#load').append('<img style="position: absolute; left: 0; top: 0; z-index: 100000;" src="/images/loading.gif" />');

      var url = $(this).attr('href');
      ajaxSearch(url, false, ".search_results");
      window.history.pushState("", "", url);
    });

    $('#add_testimonial_btn').click(function() {
        clearAllFields('#testimonial-form');
        $('#testimonial-form').attr('action', $(this).attr('data-url'));
        $('#add-edit-testimonial-modal').removeClass('hidden');
    });

    $(".close-modal").click(function() {
        $(".modal").each(function(index,element){
            if(!$(element).hasClass('hidden')) {
                $(element).addClass('hidden')
            }
        });
    });

    $(document).on('click', '.edit_testimony_btn', function(event) {
        event.preventDefault();
        $("#testimonial-form").attr('action', $(this).attr('data-url'));
        var openModal = '#add-edit-testimonial-modal';
        openEditPopup($(this).attr('data-url'), openModal);
    });

    $('#image_path').change(function() {
        readURL(this);
        $('.remove_image').removeClass('hidden');
    });

    $('.remove_image').click(function() {
        $('#image_path').val('');
        $('#image_path_preview').attr('src', base_url + '/img/camera_icon.png');
        $('.remove_image').addClass('hidden');
    });

    $(document).on('click', '.delete_testimony_btn, .restore_testimony_btn', function(event) {
        event.preventDefault();
        if($(this).hasClass('delete_testimony_btn')) {
            openDeletePopup($(this).attr('data-url'), 'DELETE');
        } else {
            openDeletePopup($(this).attr('data-url'), 'PATCH');
        }

    });

    $("#search_keywords").keyup(function() {
        var url = base_url + '/admin/testimonials';
        ajaxSearch(url, true, ".search_results");
    });

    $("#company_name").autocomplete({
        minChars: 3,
        serviceUrl: base_url + '/get_company',
        dataType: 'json'
    });

    validateTestimonialForm("#testimonial-form");
});


function validateTestimonialForm(formObj) {

    $(formObj).validate({
        rules: {
            user_name: {
                required: true,
                minlength: 3
            },
            designation: {
                required: true,
                minlength: 3
            },
            message: {
                required: true,
                minlength: 3
            },

        },
        messages: {
            user_name: {
                required: "Please enter user name",
                minlength: "Please enter at least 3 characters.",
            },
            designation: {
                required: "Please enter user designation",
                minlength: "Please enter at least 3 characters.",
            },
            message: {
                required: "Please enter message",
                minlength: "Please enter at least 3 characters.",
            },
        },
        submitHandler: function(form) {
            // do other things for a valid form
            form.submit();
        }
    });
}

function openEditPopup(url,openModal) {
    $.ajax({
        method: 'GET',
        url: url,
        dataType: 'json',
        success: function(response) {
            if(response.data != "") {
                $.each(response.data, function(index,value) {
                    if(index == "image_path") {
                        // var image_path = base_url + image_path;
                        $("#image_path_preview").attr('src',value);
                        $("#image_path_preview").addClass('h-40');
                    } else {
                        if($("#"+index).length == 1) {
                            $("#"+index).val(value);
                        }
                    }

                });
                var method = '<input type="hidden" name="_method" value="PUT"></input>';
                $(openModal + ' form').append(method);
                $(openModal).removeClass("hidden");
            }
        }


    });
}


function clearAllFields(formId) {
    $(formId + " input").each(function(key, element) {
        if($(element).attr('name') != "_method"  && $(element).attr('name') != "_token") {
            $(element).val('');
        }
    });
    $(formId + " textarea").val('');
    $(formId + " #image_path_preview").attr('src', base_url + '/img/camera_icon.png');
}

function openDeletePopup(url, method) {
    if(method == "DELETE") {
        $(".confirmation_msg_div").text('Are you sure you want to delete?');
    } else {
        $(".confirmation_msg_div").text('Are you sure you want to restore it?');
    }

    $("#delete-modal form").attr('action',url);
    $("#delete-modal input[name='_method']").val(method);
    $("#delete-modal").removeClass('hidden');
}


function ajaxSearch(ajaxUrl, addKeyword, displayDiv) {

  if(addKeyword) {
      var searchKeyword = $("#search_keywords").val();
      ajaxUrl = ajaxUrl + '?keywords=' + searchKeyword;
  }

  $.ajax({
      method: 'GET',
      url: ajaxUrl,
      dataType: 'json',
      success: function(response) {
          populateTable(response, displayDiv);
          populatePagination(response.paginator);
      },
      error: function(xhr) {

      }
  });
}

function populateTable(response, displayDiv) {

  var html = '';
  $(response.testimonials.data).each(function(key, testimony) {

    var restoreDelete = '';
    if(testimony.deleted_at == null) {
      restoreDelete = `<a href="#" data-url="${base_url + '/admin/testimonials/' + testimony.id}" title="Delete testimony" class="ml-1 delete_testimony_btn">
                          <i class="fa fa-toggle-on"></i>
                      </a>`;
    } else {
      restoreDelete = ` <a  href="#" data-url="${base_url + '/admin/testimonials/' + testimony.id}" title="Restore testimony" class="ml-1 restore_testimony_btn">
                          <i class="fa fa-toggle-off"></i>
                      </a>`;
    }

    html = `<tr class="hover:bg-gray-100 border-b border-gray-200 py-10">
              <td class="px-4 py-4">${testimony.id}</td>
              <td class="px-4 py-4">${testimony.user_name}</td>
              <td class="px-4 py-4">${testimony.designation}</td>
              <td class="px-4 py-4">${testimony.company_name ? testimony.company_name : 'NA'}</td>
              <td class="px-4 py-4">${testimony.company_website ? testimony.company_website : 'NA'}</td>
              <td class="px-4 py-4">${testimony.message}</td>
              <td class="px-4 py-4">
                  <img class="w-auto object-cover object-center h-10" id="certification_img" src="${base_url + testimony.image_path}" alt="Testimony User Image" />
              </td>
              <td class="px-4 py-4 ${testimony.deleted_at ? 'text-red-500': 'text-green-500'}">${testimony.deleted_at ? 'Deleted': 'On'}</td>
              <td class="px-4 py-4">${testimony.created_at}</td>
              <td class="px-4 py-4">

                  <a href="#" title="Edit Testimony Details" data-url="${base_url + '/admin/testimonials/' + testimony.id}" class="mx-0.5 edit_testimony_btn">
                      <i class="fa fa-pencil mx-0.5"></i>
                  </a>
                  ${restoreDelete}
              </td>
          </tr>`;
  });

  $(displayDiv).html(html);
}

function populatePagination(paginationData) {
  $('#pagination').html(paginationData);
}

