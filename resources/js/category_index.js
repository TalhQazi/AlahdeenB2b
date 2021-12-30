window.$ = window.jQuery = require('jquery');

$(document).ready(function() {
    $('.close-modal').click(function() {
        $('#delete-modal').addClass('hidden');
    });

    $("#search_keywords").keyup(function() {
        var url = base_url + '/admin/category';
        if( $(this).val() == "" ) {
            window.location.href = url;
        } else {
            ajaxSearch(url, true, ".search_results");
        }

    });

    $(document).on('click','.delete-category',function(event){
        event.preventDefault();
        $('#delete-modal').removeClass('hidden');
        $('#modal-title').text($(this).attr('title'));
        $('#delete-modal form').attr('action',$(this).attr('href'));
    });

    $('body').on('click', '#pagination a', function(e) {
        e.preventDefault();

        $('#load a').css('color', '#dfecf6');
        $('#load').append('<img style="position: absolute; left: 0; top: 0; z-index: 100000;" src="/images/loading.gif" />');

        var url = $(this).attr('href');
        ajaxSearch(url, false, ".search_results");
        window.history.pushState("", "", url);
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
    $(response.categories.data).each(function(key, category) {

        var restoreDelete = '';
        if(category.deleted_at == null) {
            restoreDelete = `<a href="${base_url+'/admin/category/delete/'+category.id}" title="Deactivate Category" class="ml-1 delete-category">
                                <i class="fa fa-toggle-on"></i>
                            </a>`;
        } else {
            restoreDelete = `<a href="${base_url+'/admin/category/restore/'+category.id}" title="Enable Category" class="ml-1 restore-category">
                                <i class="fa fa-toggle-off"></i>
                            </a>`;
        }

        var imgHtml = 'Not Provided';
        if(category.image_path != null) {
            imgHtml = `<img class="object-cover object-center" width="100" height="100" src="${base_url + category.image_path}" alt="${category.title + ' Image'}" />`;
        }

        html += `<tr class="hover:bg-gray-100 border-b border-gray-200 py-10">
                    <td class="px-4 py-4">${category.id}</td>
                    <td class="px-4 py-4">${imgHtml}</td>
                    <td class="px-4 py-4">${category.title}</td>
                    <td class="px-4 py-4">${category.parent_category != null ? category.parent_category.title : 'NA'}</td>
                    <td class="px-4 py-4 ${category.deleted_at ? 'text-red-500': 'text-green-500'}">${category.deleted_at ? 'Deleted': 'On'}</td>
                    <td class="px-4 py-4">${category.created_at}</td>
                    <td class="px-4 py-4">${category.home_page_category != null ? category.home_page_category.display_section : 'NA'}</td>
                    <td class="px-4 py-4">
                        <a href="${base_url + '/admin/category/show/' + category.id}" title="View Category Details">
                            <i class="fa fa-eye"></i>
                        </a>
                        <a href="${base_url + '/admin/category/edit/' + category.id}" title="Edit Category" class="mx-0.5">
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
