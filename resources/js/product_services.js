const { ajax } = require('jquery');
const { replace } = require('lodash');

window.$ = window.jQuery = require('jquery');
require('devbridge-autocomplete');

$(document).ready(function() {

    $(".secondary_business").click(function() {
        var targetDiv = $(this).attr('data-business-target');
        if($("#keywords_input_"+targetDiv).hasClass('hidden')) {
            $("#keywords_input_"+targetDiv).removeClass('hidden');
        } else {
            $("#keywords_input_"+targetDiv).addClass('hidden');
        }
    });

    $(".keywords").autocomplete({
        minChars: 3,
        serviceUrl: base_url + '/product/get_products',
        dataType: 'json',
        preserveInput: false,
        onSelect: function (suggestion) {
            var suggestionSpanId = (suggestion.value).replace(/ /gi, "_").toLowerCase().replace(/[^\w\s]/gi, '_');
            var inputId = $(this).attr('data-target-div');
            if($(this).attr('id') == "main_keywords_input") {
                if( $("#" + suggestionSpanId).length < 1) {

                    var selectedProductHtml = "<span id='" + suggestionSpanId + "' class='inline-block rounded-full text-gray-600 bg-green-200 px-4 py-2 text-xs font-bold mr-3'>"+
                                                    suggestion.value +
                                                    "<i class='fa fa-times ml-2 delete-keyword' data-prodservice-id='' data-keyword='" + suggestion.value + "' data-target-parent='" + suggestion.value + "'></i>"+
                                                    "<input type='hidden' name='main_keywords[]' value='" + suggestion.value + "'>" +
                                                "</span>";
                    $(inputId).append(selectedProductHtml);
                }
            } else {

                var businessTypeId = inputId.replace("#secondary_products_","");
                if( $("#" + businessTypeId + '_' + suggestionSpanId).length < 1) {
                    var selectedProductHtml = "<span id='"+ businessTypeId + '_' + suggestionSpanId + "' class='inline-block rounded-full text-gray-600 bg-green-200 px-4 py-2 text-xs font-bold mr-3'>"+
                                                    suggestion.value +
                                                    "<i class='fa fa-times ml-2 delete-keyword' data-prodservice-id='' data-keyword='" + suggestion.value + "' data-target-parent='" + businessTypeId + ' ' + suggestion.value + "'></i>" +
                                                    "<input type='hidden' name='secondary_keywords[" + businessTypeId + "][]' value='" + suggestion.value + "'>" +
                                                "</span>";
                    $(inputId).append(selectedProductHtml);
                }
            }

            $(this).val("");
        }
    });

    $(document).on("click", ".delete-keyword", function() {
        $("#delete-modal").removeClass('hidden');

        $("#product_service_id").val($(this).attr('data-prodservice-id'));
        $("#deleted_keyword").val($(this).attr('data-keyword'));
        $("#parent_span_id").val($(this).attr('data-target-parent'));

    });

    $("#update_keywords_form").submit(function(event) {
        event.preventDefault();


        var formData = {};
        var productServiceId = $("#product_service_id").val();
        formData.deleted_keyword = $("#deleted_keyword").val();
        formData._token = $('meta[name="csrf-token"]').attr('content')


        var productSpanId = ($("#parent_span_id").val()).replace(/ /gi, "_").toLowerCase().replace(/[^\w\s]/gi, '_');
        $("#delete-modal").addClass('hidden');
        $("#"+productSpanId).remove();

        if(productServiceId !== "") {

            $.ajax({
                method: 'POST',
                url: base_url + '/product-services/update/' + productServiceId,
                dataType: 'json',
                data: formData,
                success: function(response) {
                    location.reload();
                }
            });
        }
    });
});
