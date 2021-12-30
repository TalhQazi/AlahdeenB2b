window.$ = window.jQuery = require('jquery');

$(function () {
    $("#q").keyup(function() {
        if($(this).val().length > 0 && $(this).val().length < 3) {
          return false;
        } else {
            var url = base_url + '/khata/inventory/definitions';
            ajaxSearch(url, true);
        }
      });

      $('body').on('click', '#pagination a', function(e) {
        e.preventDefault();

        $('#load a').css('color', '#dfecf6');
        $('#load').append('<img style="position: absolute; left: 0; top: 0; z-index: 100000;" src="/images/loading.gif" />');

        var url = $(this).attr('href');
        ajaxSearch(url, false);
        window.history.pushState("", "", url);
      });

    $(document).on('click', '.remove_definition', function (e) {
        e.preventDefault();
        let $this = $(this);
        if (confirm('Are you sure you want to remove this product pricing from the inventory?')) {
            $this.parents('form').submit();
        } else {
            return false;
        }
    });

});

function ajaxSearch(ajaxUrl, addKeyword) {

    if(addKeyword) {
        var searchKeyword = $("#q").val();
        ajaxUrl = ajaxUrl + '?keywords=' + searchKeyword;
    }

    $.ajax({
        url: ajaxUrl,
        dataType: 'json',
        method: 'get',
        success: function(response) {
            if(response.product_definitions.length > 0) {
                populateTable(response);
                populatePagination(response.paginator);
            } else {
                var html = `<tr class="hover:bg-gray-100 border-b border-gray-200 py-10">
                    <td colspan="5" class="pxi-4 py-4 text-center">
                        No Inventory Products Definition found
                    </td>
                </tr>`;

                $("#search_results").html(html);
            }

        }
    });
}


function populateTable(response) {

    var html = '';
    $(response.product_definitions).each(function(key, definition) {
      html +=  `<tr class="hover:bg-gray-100 border-b border-gray-200 py-10">
      <td class="px-4 py-4">${definition.product_code}</td>
      <td class="px-4 py-4">${definition.product.title}</td>
      <td class="px-4 py-4">${definition.purchase_unit}</td>
      <td class="px-4 py-4">${definition.conversion_factor}</td>
      <td class="px-4 py-4">${definition.purchase_production_interval + ' ' +  definition.purchase_production_unit}</td>
      <td class="px-4 py-4 controls">
            <form action="${base_url+'/khata/inverntory_definitions/' + definition.id}"
                method="POST">
                <input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr('content')}">
                <input type="hidden" name="_method" value="DELETE">
                <span>
                    <a href="${base_url+'/khata/inventory/product-definition/' + definition.id +'/edit'}" title="Edit Product Definition">
                        <i class="far fa-pencil fa-lg"></i>
                    </a>
                </span>
                <span>
                  <button class="remove_definition" type="submit" title="{{ __('Remove Product Definition') }}">
                      <i class="far fa-trash fa-lg"></i>
                  </button>
                </span>
          </form>
      </td>
    </tr>`;
    });

    $("#search_results").html(html);
}

function populatePagination(paginationData) {
    $('#pagination').html(paginationData);
}

