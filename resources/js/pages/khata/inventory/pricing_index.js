window.$ = window.jQuery = require('jquery');
require('jquery-validation');
require('jquery-validation/dist/additional-methods.js');

$(function() {

    $("#q").keyup(function() {
        var url = base_url + '/khata/inventory/pricing';
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

    $(document).on('click', '.remove_pricing', function (e) {
        e.preventDefault();
        let $this = $(this);
        if (confirm('Are you sure you want to remove this product pricing from the inventory?')) {
            $this.parents('form').submit();
        } else {
            return false;
        }
    });


});

function ajaxSearch(ajaxUrl, addKeyword, displayDiv) {

    if(addKeyword) {
        var searchKeyword = $("#q").val();
        ajaxUrl = ajaxUrl + '?keywords=' + searchKeyword;
    }

    $.ajax({
        method: 'GET',
        url: ajaxUrl,
        dataType: 'json',
        success: function(response) {
            if(response.products_pricing.data.length > 0) {
                populateTable(response, displayDiv);
                populatePagination(response.paginator);
            } else {
                var html = `<tr class="hover:bg-gray-100 border-b border-gray-200 py-10">
                    <td colspan="5" class="pxi-4 py-4 text-center">
                        No Inventory Products Pricing found
                    </td>
                </tr>`;

                $("#search_results").html(html);
            }
        },
        error: function(xhr) {

        }
    });
}

function populateTable(response, displayDiv) {

    var html = '';
    $(response.products_pricing.data).each(function(key, pricing) {

        var imgHtml = 'Not Provided';
        if(pricing.product.images.data != null) {
            imgHtml = `<img class="object-cover object-center" width="100" height="100" src="${base_url + pricing.product.images.data[0].path}" alt="${pricing.product.title + ' Image'}" />`;
        }

        html += `<tr class="hover:bg-gray-100 border-b border-gray-200 py-10">
                    <td class="px-4 py-4">${pricing.product.id}</td>
                    <td class="px-4 py-4">${imgHtml}</td>
                    <td class="px-4 py-4">${pricing.product.title}</td>
                    <td class="px-4 py-4">${pricing.price_per_unit}</td>
                    <td class="px-4 py-4">${pricing.avg_cost_per_unit}</td>
                    <td class="px-4 py-4">${pricing.sales_tax_percentage}</td>
                    <td class="px-4 py-4">${pricing.discount_percentage}</td>
                    <td class="px-4 py-4 controls">
                        <form action="${base_url+'/khata/inverntory/product-pricing/' + pricing.id}" method="POST">
                            <input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr('content')}">
                            <input type="hidden" name="_method" value="DELETE">
                            <span>
                                <a href="${base_url+'/khata/inventory/product-pricing/' + pricing.id +'/edit'}" title="Edit Product Pricing">
                                    <i class="far fa-pencil fa-lg"></i>
                                </a>
                            </span>
                            <span>
                                <button class="remove_pricing" type="submit" title="{{ __('Remove Product Pricing') }}">
                                    <i class="far fa-trash fa-lg"></i>
                                </button>
                            </span>
                        </form>
                    </td>
                </tr>`;
    });

    $(displayDiv).html(html);
}

function populatePagination(paginationData) {
    $('#pagination').html(paginationData);
}

