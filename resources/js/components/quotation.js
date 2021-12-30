
import intlTelInput from "intl-tel-input";
var currentStep = 0;
var steps = $('.quotation_tab');

var productsQuoteReq = [];

$(document).ready(function () {

  // initializing phone number country selector
  var input = document.querySelector("#phone");
  var no = intlTelInput(input, {
    utlisScript: $.getScript(base_url + '/js/utils/utils.js'),
    separateDialCode: true,
    placeholderNumberType: "MOBILE",
    initialCountry: "auto",
    hiddenInput: "phone_full",
    geoIpLookup: function (success, failure) {
      $.get("https://ipinfo.io?token=37910420e39b85", function () { }, "jsonp").always(function (resp) {
        var countryCode = (resp && resp.country) ? resp.country : "pk";
        success(countryCode);
      });
    },
  });



  $("#add_more_products").click(function (e) {
    e.preventDefault();
    var productCounter = $("#product_counter").val();
    var html = addMoreProducts(productCounter);

    $(`#quotation_div_${productCounter - 1}`).after(html);
    $('#unit_0').find('option').clone().appendTo(`#unit_${productCounter}`);

    productCounter++;
    $("#product_counter").val(productCounter);

  });

  $(document).on('click', '.remove_product', function (e) {
    e.preventDefault();
    $(this).parents('.quotation_div').remove();
  });

  $("#quote_btn").click(function () {
    var buyerId = $('.participants.active').attr('data-buyer-id');
    $('#buyer_id').val(buyerId);

    if(!$("#quotation_request_details").hasClass('hidden')) {
        $("#quotation_request_details").addClass('hidden');
    }

    $("#quotation-modal").removeClass('hidden');
  });

  $("#request_quote_btn").click(function () {
    var sellerId = $('.participants.active').attr('data-buyer-id');
    $('#req_seller_id').val(sellerId);
    getSellerProducts(sellerId, '');
  });


  $("#products_search_bar").keyup(function () {
    var sellerId = $('.participants.active').attr('data-buyer-id');
    getSellerProducts(sellerId, $(this).val());
  });

  $(document).on('click', '.is_quote_required', function () {
    if ($(this).is(':checked')) {
      productsQuoteReq.push($(this).attr('data-product-id'));
    } else {
      const index = productsQuoteReq.indexOf($(this).attr('data-product-id'));
      if (index > -1) {
        productsQuoteReq.splice(index, 1);
      }
    }
  });

  $("#req_quote_btn").click(function (e) {
    e.preventDefault();
    $("#request_quotation_form").submit();
  });

  $("#request_quotation_form").submit(function (e) {
    e.preventDefault();
    var activeConversationId = $('.participants.active').attr('data-conversation-id');
    $("#req_conversation_id").val(activeConversationId);
    var formData = new FormData(this);
    formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
    sendQuotationRequest(formData);
  });



  $("#quotation_form").submit(function (e) {
    e.preventDefault();

    let iti = window.intlTelInputGlobals.getInstance(document.querySelector('#phone'));
    $("input[name='phone_full'").val(iti.getNumber());

    if (currentStep == (steps.length - 1)) {

      var activeConversationId = $('.participants.active').attr('data-conversation-id');
      $("#generate_pdf #conversation_id").val(activeConversationId);
      var formData = new FormData(this);
      formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
      sendQuotation(formData);
      if (!$("#quotation-modal").hasClass('hidden')) {
        $("#quotation-modal").addClass('hidden');
      }
    } else {
      var formData = new FormData(this);
      formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
      $("#next_step").prop("disabled", true);
      createQuotation(formData);
    }
  });

  $("#next_step").click(function (e) {
    e.preventDefault();
    validateForm('#quotation_form');

    if (validateCurrentForm()) {
      if (currentStep == 0) {
        $("#previous_step").removeClass('hidden');
      } else if (currentStep == (steps.length - 2) || currentStep == (steps.length - 1)) {
        $("#quotation_form").submit();
      }

      hideTab(currentStep);
      currentStep++;
      showTab(currentStep);
    }
  });

  $("#previous_step").click(function (e) {
    e.preventDefault();

    hideTab(currentStep);
    currentStep--;
    showTab(currentStep);

    if (currentStep == 0) {
      $("#previous_step").addClass('hidden');
    }

  });

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

        var fieldNo = $(this).attr('id').replace('product_', '');
        $(`#price_${fieldNo}`).val(suggestion.data.price);
        $(`#description_${fieldNo}`).text(suggestion.data.description.replace(/(<([^>]+)>)/gi, ""));
        if (suggestion.data.images != null) {
          var imagesCount = Object.keys(suggestion.data.images.data).length;
          var imgIsSet = false;
          for (var index = 0; index < imagesCount; index++) {
            if (suggestion.data.images.data[index].is_main == "1") {
              var imgUrl = base_url + suggestion.data.images.data[index].path;
              $(`#q_image_${fieldNo}_preview`).attr('src', imgUrl);
              $(`#q_image_${fieldNo}_preview`).css('width', '160px');
              $(`#q_image_${fieldNo}_preview`).css('height', '160px');
              $(`#q_image_${fieldNo}_preview`).next('.remove_image').removeClass('hidden');
              $(`#q_image_path_${fieldNo}`).val(suggestion.data.images.data[index].path);

              imgIsSet = true;
            }
          }

          if (!imgIsSet) {
            var imgUrl = base_url + suggestion.data.images.data[0].path;
            $(`#q_image_${fieldNo}_preview`).attr('src', imgUrl);
            $(`#q_image_${fieldNo}_preview`).css('width', '160px');
            $(`#q_image_${fieldNo}_preview`).css('height', '160px');
            $(`#q_image_${fieldNo}_preview`).next('.remove_image').removeClass('hidden');
            $(`#q_image_path_${fieldNo}`).val(suggestion.data.images.data[index].path);

          }
        }
      }


    });
  });

  $(document).on('click', '.view_quotation_request', function () {
    viewQuotationRequest($(this).attr('data-quote-req-id'), 'quotation-request');
    var buyerId = $('.participants.active').attr('data-buyer-id');
    $('#buyer_id').val(buyerId);
  });

  $(document).on('click', '.contact_buyer', function () {
    var leadId = $(this).attr('data-req-id');
    var buyerId = $(this).attr('data-buyer-id');
    $.ajax({
      method: 'GET',
      dataType: 'json',
      url: base_url + '/subscription/check-package',
      success: function (response) {
        if (response.can_contact == 1) {
          viewQuotationRequest(leadId, 'buying_requirement');
          $('#buyer_id').val(buyerId);
          $("#post_buy_req_id").val(leadId);
        } else {
          $("#error_message").text('Need to purchase one of the packages before you can contact the buyer');
          $("#error_message").parent('div').removeClass('hidden');
        }


      }
    });

  });

  $(".close-modal").click(function() {
    $(".modal").each(function(index,element){

        if(!$(element).hasClass('hidden')) {
            $(element).addClass('hidden')
        }
    });
});

});

function hideTab(n) {

  var currentDiv = $(steps[n]).attr('data-target');
  $(currentDiv).addClass('hidden');
  $(steps[n]).removeClass('active');
}

function showTab(n) {

  var targetDiv = $(steps[n]).attr('data-target');
  $(targetDiv).removeClass('hidden');
  $(steps[n]).addClass('active');

  if (n == (steps.length - 1)) {
    $("#next_step").text("Send Quote");
  } else if (n == (steps.length - 2)) {
    $("#next_step").text("Generate Quotation");
  } else {
    $("#next_step").text("Next");
  }
}

function addMoreProducts(productCounter) {


  var html = `<div class="grid grid-rows-3 grid-flow-col gap-4 alert alert-light alert-close mb-5 mt-10 quotation_div" id="quotation_div_${productCounter}">
                    <div class="row-span-1 col-span-12 row-end-1 flex justify-end">
                        <button class="alert-btn-close  remove_product">
                            <i class="fad fa-times"></i>
                        </button>
                    </div>
                    <div class="bg-white px-4 py-5 rounded-lg shadow-lg text-center w-48 row-span-3 col-span-3 img_div">
                        <div class="mb-4">
                            <img class="w-auto mx-auto object-cover object-center" id="q_image_${productCounter}_preview" src="` + base_url + `/img/camera_icon.png" alt="Upload File" />
                            <i class="fa fa-times text-red-500 relative hidden remove_image" style="top: -180px; left: 88px;"></i>
                        </div>
                        <label class="cursor-pointer mt-6">
                            <span class="mt-2 text-base leading-normal px-4 py-2 bg-blue-500 text-white rounded-full" >Attachment</span>
                            <input type="hidden" name="q_image_path[]" id="q_image_path_${productCounter}">
                            <input type='file' name="q_image[]" id="q_image_${productCounter}" class="hidden photo" :accept="accept" />
                        </label>
                    </div>
                    <div class="row-span-3 col-span-9">
                        <div class="col-span-12">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="product">
                                Product
                            </label>
                            <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500 product_autocomplete" name="product[]" id="product_${productCounter}" type="text">
                        </div>
                        <div class="grid grid-cols-12 gap-2 mt-3">
                            <div class="col-span-4">
                                <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='grid-text-1'>Quantity</label>
                                <input class='appearance-none block bg-white text-gray-700 border px-4 w-32 border-gray-400 shadow-inner rounded-md py-3 leading-tight focus:outline-none  focus:border-gray-500' name="quantity[]" id="quantity_${productCounter}" type="text">
                            </div>
                            <div class="col-span-4">
                                <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='grid-text-1'>Price</label>
                                <input class='appearance-none block bg-white text-gray-700 border px-4 w-32 border-gray-400 shadow-inner rounded-md py-3 leading-tight focus:outline-none  focus:border-gray-500' name="price[]" id="price_${productCounter}" type="text">
                            </div>
                            <div class="col-span-4">
                                <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'>Unit</label>
                                <div class="flex-shrink w-full inline-block relative">
                                    <select class="block appearance-none text-gray-700 w-full bg-white border border-gray-400 shadow-inner px-4 py-3 pr-8 rounded leading-tight" name="unit[]" id="unit_${productCounter}">
                                    </select>
                                    <div class="pointer-events-none absolute top-0 mt-3  right-0 flex items-center px-2 text-gray-600">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-12 mt-3">
                            <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='grid-text-1'>Product Description</label>
                            <textarea name="description[]" id="description_${productCounter}" cols="30" rows="3" class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500'></textarea>
                        </div>
                    </div>

                </div>`;

  return html;
}


function validateForm(formObj) {

  $(formObj).validate({
    ignore: ":not(:visible)",
    rules: {
      "product[]": {
        required: true,
      },
      "quantity[]": {
        required: true,
        number: true,
      },
      "price[]": {
        required: true,
        number: true,
      },
      "unit[]": {
        required: true
      },
      'phone': {
        required: true,
      },
      'address': {
        required: true
      }
    },
    messages: {
      "product[]": {
        required: "Please enter product",
      },
      "quantity[]": {
        required: "Please enter quantity",
        number: "Quantity can only consist of numbers or be in decimal form"
      },
      "price[]": {
        required: "Please enter price",
        number: "Price can only consist of numbers or be in decimal form"
      },
      "unit[]": {
        required: "Please select one of the units from dropdown",
      },
      'phone': {
        required: "Please provide phone number",
      },
      'address': {
        required: "Please provide address"
      }
    }
  });

}

function validateCurrentForm() {
  return $('#quotation_form').valid();
}

function createQuotation(formData) {
  $.ajax({
    method: 'POST',
    url: base_url + '/quotation',
    dataType: 'json',
    data: formData,
    cache: false,
    contentType: false,
    processData: false,
    success: function (response) {

      $(".quotation_link").attr('href', base_url + response.file_path);
      $("#generate_pdf #quotation_path").val(response.file_path);
      $("#next_step").prop('disabled', false);
    },
    error: function (xhr, textStatus, errorThrown) {
      console.log(xhr.responseText);
    }
  });
}

function sendQuotation(formData) {

  $.ajax({
    method: 'POST',
    url: base_url + '/quotation/send',
    dataType: 'json',
    data: formData,
    cache: false,
    contentType: false,
    processData: false,
    success: function (response) {
      if ('sent_message' in response) {

        $(response.sent_message).each(function (key, message) {
          createMsgSent(message, 'justify-end');
        });

        if($('.participants.active .last_message').length > 1) {

          $('.participants.active .last_message').text(response.last_message);
          $("#quotation_form input").val('');
          $("#quotation_form select").val('');
          $("#quotation_form textarea").val('');

          $("#success_message").text("Quotation has been sent successfully");
          $("#success_message").parent('div').removeClass('hidden');

          showTab(0);
        } else {
          window.location.href = base_url + '/chat/messages';
        }

      } else {
        $("#error_message").text(response.message);
        $("#error_message").parent('div').removeClass('hidden');
      }

    }
  });
}

function createMsgSent(message, justifyClass) {
  var html = '<div class="flex ' + justifyClass + ' mb-4">';
  html += '<div class="mr-2 py-3 px-4 bg-blue-400 rounded-bl-3xl rounded-tl-3xl rounded-tr-xl text-white max-w-md break-words">';
  html += message;
  html += '</div>';
  html += '</div>';
  $('.messages').append(html);
}

$.validator.prototype.checkForm = function () {
  //overriden in a specific page
  this.prepareForm();
  for (var i = 0, elements = (this.currentElements = this.elements()); elements[i]; i++) {
    if (this.findByName(elements[i].name).length !== undefined && this.findByName(elements[i].name).length > 1) {
      for (var cnt = 0; cnt < this.findByName(elements[i].name).length; cnt++) {
        this.check(this.findByName(elements[i].name)[cnt]);
      }
    } else {
      this.check(elements[i]);
    }
  }
  return this.valid();
};

function getSellerProducts(sellerId, searchParam) {
  $.ajax({
    method: 'GET',
    url: base_url + '/product/get_seller_products/?seller_id=' + sellerId + '&keywords=' + searchParam,
    dataType: 'json',
    success: function (response) {
      createReqQuoteForm(response);

    }
  });
}

function createReqQuoteForm(response) {
  var html = '';
  $(response.data).each(function (key, productDetails) {
    if (typeof (productDetails.images.data) != "undefined") {
      var imagePath = productDetails.images.data[0].path;
    }

    var isChecked = productsQuoteReq.indexOf(String(productDetails.id)) > -1 ? "checked" : "";

    html += `<div class="grid grid-rows-3 grid-flow-col gap-4 alert alert-light alert-close mb-5" id="request_quotation_div_${key}">
                    <div class="row-span-1 col-span-12 row-end-1 flex justify-end">
                        <input type="checkbox" name="product[${key}][is_required]" class="is_quote_required" data-product-id="${productDetails.id}" ${isChecked}>
                        <input type="hidden" name="product[${key}][title]" value="${productDetails.title}">
                        <input type="hidden" name="product[${key}][id]" value="${productDetails.id}">
                    </div>
                    <div class="bg-white px-4 py-5 rounded-lg shadow-lg text-center w-48 row-span-3 col-span-3 img_div">
                        <div class="mb-4">
                            <img class="mx-auto object-cover object-center w-32 h-32" src="${base_url + imagePath}"/>
                            <i class="fa fa-times text-red-500 relative hidden remove_image" style="top: -180px; left: 88px;"></i>
                        </div>
                    </div>
                    <div class="row-span-3 col-span-9">

                        <div class="col-span-12">
                            <span class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                                ${productDetails.title}
                            </span>

                        </div>
                        <div class="grid grid-cols-12 gap-2 mt-3">
                            <div class="col-span-4">
                                <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='grid-text-1'>Budget</label>
                                <input class='appearance-none block bg-white text-gray-700 border px-4 w-full border-gray-400 shadow-inner rounded-md py-3 leading-tight focus:outline-none  focus:border-gray-500' name="product[${key}][budget]" id="budget_0" type="text">
                            </div>
                            <div class="col-span-4">
                                <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='grid-text-1'>Quantity</label>
                                <input class='appearance-none block bg-white text-gray-700 border px-4 w-full border-gray-400 shadow-inner rounded-md py-3 leading-tight focus:outline-none  focus:border-gray-500' name="product[${key}][quantity]" id="quantity_0" type="text">
                            </div>
                            <div class="col-span-4">

                                <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'>Unit</label>
                                <div class="flex-shrink w-full inline-block relative">
                                    <select class="block appearance-none text-gray-700 w-full bg-white border border-gray-400 shadow-inner px-4 py-3 pr-8 rounded leading-tight" name="product[${key}][unit]" id="unit_0">
                                        <option value="">/ Unit</option>
                                        <option value="pieces">pieces</option>

                                    </select>
                                    <div class="pointer-events-none absolute top-0 mt-3  right-0 flex items-center px-2 text-gray-600">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-12 mt-3">
                            <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='grid-text-1'>Your Requirements</label>
                            <textarea name="product[${key}][requirements]" id="requirements_0" cols="30" rows="3" class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500'></textarea>
                        </div>
                    </div>
                </div>`;
  });

  $("#seller_products").html(html);
  $("#request-quotation-modal").removeClass('hidden');
}

function sendQuotationRequest(formData) {
  $.ajax({
    method: 'POST',
    url: base_url + '/quotation/request',
    dataType: 'json',
    data: formData,
    cache: false,
    contentType: false,
    processData: false,
    success: function (response) {
      if ('sent_message' in response) {

        $(response.sent_message).each(function (key, message) {
          createMsgSent(message, 'justify-end');
        });

        $('.participants.active .last_message').text(response.last_message);


        $("#success_message").text("Enquiry has been sent successfully");
        $("#success_message").parent('div').removeClass('hidden');

        $("#request-quotation-modal").addClass('hidden');

      } else if ('error_message' in response) {

      } else {
        $("#error_message").text(response.error);
        $("#error_message").parent('div').removeClass('hidden');
      }

    }
  });
}

function viewQuotationRequest(quoteReqId, requestType) {
  var url = base_url + '/quotation/request/' + quoteReqId;
  if (requestType == "buying_requirement") {
    url = base_url + '/product-buy-requirement/' + quoteReqId;
  }

  $("#quotation_request_details").html('');

  $.ajax({
    method: 'GET',
    dataType: 'json',
    url: url,
    success: function (response) {
      if (requestType == "buying_requirement") {
        var html = populateBuyingReq(response);
      } else {
        var html = populateReqQuoteDetails(response);
      }

      $("#quotation_request_details").html(html);
      $("#quotation_request_details").removeClass('hidden');
      $("#quotation-modal").removeClass('hidden');
    }
  });
}

function openQuotationRef(key, requestDetails, detailsLength) {

  $(`#product_${key}`).val(requestDetails.product.title);
  $(`#price_${key}`).val(requestDetails.budget);
  $(`#description_${key}`).text(requestDetails.product.description.replace(/(<([^>]+)>)/gi, ""));
  if (requestDetails.product.images != null) {
    var imagesCount = Object.keys(requestDetails.product.images).length;
    var imgIsSet = false;
    for (var index = 0; index < imagesCount; index++) {
      if (requestDetails.product.images[index].is_main == "1") {
        var imgUrl = base_url + '/' + requestDetails.product.images[index].path.replace('public', 'storage');
        $(`#q_image_${key}_preview`).attr('src', imgUrl);
        $(`#q_image_${key}_preview`).css('width', '160px');
        $(`#q_image_${key}_preview`).css('height', '160px');
        $(`#q_image_${key}_preview`).next('.remove_image').removeClass('hidden');
        $(`#q_image_path_${key}`).val(requestDetails.product.images[index].path);

        imgIsSet = true;
      }
    }

    if (!imgIsSet) {
      var imgUrl = base_url + '/' + requestDetails.product.images[0].path.replace('public', 'storage');
      $(`#q_image_${key}_preview`).attr('src', imgUrl);
      $(`#q_image_${key}_preview`).css('width', '160px');
      $(`#q_image_${key}_preview`).css('height', '160px');
      $(`#q_image_${key}_preview`).next('.remove_image').removeClass('hidden');
      $(`#q_image_path_${key}`).val(requestDetails.product.images[index].path);

    }
  }

  if (key < detailsLength - 1) {

    var productCounter = $("#product_counter").val();
    var html = addMoreProducts(productCounter);

    $(`#quotation_div_${productCounter - 1}`).after(html);
    $('#unit_0').find('option').clone().appendTo(`#unit_${productCounter}`);

    productCounter++;
    $("#product_counter").val(productCounter);
  }

}

function populateReqQuoteDetails(response) {
    var html = '';

    $(response.details).each(function (key, requestDetails) {

        var requirementDetails = requestDetails.requirements == null ? 'N/A' : requestDetails.requirements;

        if (key == 0) {
        html += `<span class="text-black">Quotation Request Reference#: ${requestDetails.quotation_request_id}</span>`;
        $("#create_quote_ref_btn").attr('data-quote-ref', requestDetails.quotation_request_id);
        }

        html += `<div class="mt-5">
                    <span>Product: ${requestDetails.product.title}</span>
                    </div>
                    <div>
                        <span>Requirement Details: <span>
                        </span>${requirementDetails}</span>
                    </div>
                    <div>
                        <span>Quantity: ${requestDetails.quantity} ${requestDetails.unit}</span>
                    </div>
                    <div>
                        <span>Budget: Rs ${requestDetails.budget}</span>
                    </div>
                </div>`;
    });

    if(response.buyer_id != user_id) {
        $(response.details).each(function (key, requestDetails) {
            openQuotationRef(key, requestDetails, response.details.length);
        });
        $("#quotation_details").removeClass('hidden');
    } else {
        if(!$("#quotation_details").hasClass('hidden')) {
            $("#quotation_details").addClass('hidden');
        }
    }



    return html;
}

function populateBuyingReq(response) {
  var html = '';
  var requirementDetails = response.requirementDetails == null ? 'N/A' : response.requirementDetails;

  html += `<div class="mt-5">
              <span>Product: ${response.required_product}</span>
              </div>
              <div>
                  <span>Requirement Details: <span>
                  </span>${requirementDetails}</span>
              </div>
              <div>
                  <span>Quantity: ${response.quantity} ${response.unit}</span>
              </div>
              <div>
                  <span>Budget: Rs ${response.budget}</span>
              </div>
              ${response.attachment ? `<div><span><a target="_blank" href="${response.attachment}">View Attachment</a></span></div>` : ''}
          </div>`;

  return html;

}
