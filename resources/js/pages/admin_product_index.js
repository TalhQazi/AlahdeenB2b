window.$ = window.jQuery = require('jquery');

$(document).ready(function() {
    $("#search_keywords").keyup(function() {
        var url = base_url + '/admin/product';

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

    $(document).on('click', '.is_featured', function() {
      var isFeatured = this.checked;
      var productId = $(this).attr('data-product-id');
      $.ajax({
        url: base_url + '/product/set-as-featured/' + productId,
        method: 'post',
        data: {
          'is_featured': isFeatured ? 1 : 0,
          '_token': $('meta[name="csrf-token"]').attr('content'),
        },
        success: function(response) {
          if(response.alertClass == 'alert-success') {
            $("#success_message").text(response.message);
            $("#success_message").parent('div').removeClass('hidden');
          } else {
            $("#error_message").text(response.message);
            $("#error_message").parent('div').removeClass('hidden');

            isFeatured ? $(`#is_featured_${productId}`).prop('checked', false) : $(`#is_featured_${productId}`).prop('checked', true);
          }
        }
      });
    });

    $(".error-btn-close").click(function() {
      $(this).parent('div').addClass('hidden');
    });
});

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
  $(response.products.data).each(function(key, product) {

    var restoreDelete = '';
    if(product.deleted_at == null) {
      restoreDelete = `<a href="${base_url+'/product/delete/'+product.id}" title="Deactivate Product" class="ml-1 delete-product">
                        <i class="fa fa-toggle-on"></i>
                      </a>`;
    } else {
      restoreDelete = `<a href="${base_url+'/product/restore/'+product.id}" title="Enable Product" class="ml-1 restore-product">
                        <i class="fa fa-toggle-off"></i>
                      </a>`;
    }

    var imgHtml = 'Not Provided';
    if(product.images.data.length > 0) {
        imgHtml = `<img class="object-cover object-center" width="100" height="100" src="${base_url + product.images.data[0].path}" alt="${product.title + ' Image'}" />`
    }

    html += `  <tr class="hover:bg-gray-100 border-b border-gray-200 py-10">
                <td class="px-4 py-4">${product.id}</td>
                <td class="px-4 py-4"><input class="shadow-inner rounded-md py-3 px-4 leading-tight is_featured" id="is_featured_${product.id}" type="checkbox" ${product.is_featured == '1' ?  'checked' : ''}  data-product-id="${product.id}"></td>
                <td class="px-4 py-4">${imgHtml}</td>
                <td class="px-4 py-4">${product.title}</td>
                <td class="px-4 py-4">${product.price}</td>
                <td class="px-4 py-4 ${product.deleted_at ? 'text-red-500': 'text-green-500'}">${product.deleted_at ? 'Deleted': 'On'}</td>
                <td class="px-4 py-4">${product.category.title}</td>
                <td class="px-4 py-4">${product.created_by.name}</td>
                <td class="px-4 py-4">${product.created_at}</td>
                <td class="px-4 py-4">
                    <a href="${base_url + '/admin/product/show/' + product.id}" title="View Product Details">
                        <i class="fa fa-eye"></i>
                    </a>
                    <a href="${base_url + '/admin/product/edit/' + product.id}" title="Edit Product" class="mx-0.5">
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

