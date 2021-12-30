window.$ = window.jQuery = require('jquery');
require('jquery-validation');
require('devbridge-autocomplete');

$(function () {

    $("#search_keywords").on('keyup', function () {
        var url = base_url + '/product-buy-requirement';
        ajaxSearch(url, true, ".search_results");
    });

    $('body').on('click', '#pagination a', function (e) {
        e.preventDefault();

        $('#load a').css('color', '#dfecf6');
        $('#load').append('<img style="position: absolute; left: 0; top: 0; z-index: 100000;" src="/images/loading.gif" />');

        var url = $(this).attr('href');
        ajaxSearch(url, false, ".search_results");
        window.history.pushState("", "", url);
    });

    $('.sll').on('click', function () {
        $(this).attr('disabled', true);
        shortListLeadToggle(this);
    });

    $('#filter-short-listed').on('click', function () {
        window.location.href = buy_requests_url + getFilterQueryString();
    });

});

function shortListLeadToggle(ref) {

    let id = Number($(ref).attr('id').replace('sll-', ''));

    $.get(sl_url, { 'id': id }).done(function (data) {
        switch (data.status) {
            case 201:
                $(ref).children('i').removeClass('far').addClass('fas');
                break;
            case 204:
                $(ref).children('i').removeClass('fas').addClass('far');
                if ($('#filter-short-listed').is(":checked")) {
                    $(ref).parent('.buy-request').hide();
                }
                break;
            default:
                alert(data.msg);
        }
    }).always(function () {
        $(ref).attr('disabled', false);
    });
}


function ajaxSearch(ajaxUrl, addKeyword, displayDiv) {

    if (addKeyword) {
        ajaxUrl += getFilterQueryString();
    }

    $.ajax({
        method: 'GET',
        url: ajaxUrl,
        dataType: 'json',
        success: function (response) {
            populateTable(response, displayDiv);
            populatePagination(response.paginator);
        },
        error: function (xhr) {
            // location.reload();
        }
    });
}

function getFilterQueryString() {
    let qsObj = {};

    if ($('#filter-short-listed').is(":checked")) {
        qsObj['f'] = 'shortlisted';
    }

    if ($("#search_keywords").val() != '') {
        qsObj['keywords'] = $("#search_keywords").val();
    }

    let qs = $.param(qsObj);

    return qs != '' ? '?' + qs : '';
}


function populateTable(responseData, displayDiv) {
    $('.search-results').html('');
    var html = '';
    var count = 1;
    $(responseData.products_buy_requirements).each(function (key, value) {


        html += `<tr class="hover:bg-gray-100 border-b border-gray-200 py-10 search-results">';
          <td class="px-4 py-4">${count}</td>
          <td class="px-4 py-4">${value.required_product}</td>
          <td class="px-4 py-4">${value.buyer.name}</td>
          <td class="px-4 py-4">${value.quantity}</td>
          <td class="px-4 py-4">${value.unit}</td>
          <td class="px-4 py-4">${value.budget}</td>
          <td class="px-4 py-4">${value.requirement_urgency}</td>
          <td class="px-4 py-4">${value.requirement_frequency}</td>
          <td class="px-4 py-4">${value.created_at}</td>
          <td class="px-4 py-4 flex flex-row">
            <button class="btn contact_buyer" data-req-id="${value.id}" data-buyer-id="${value.buyer.id}">Contact Buyer</button>
            <div class="sll pl-2 pt-3" id="sll-${value.id}">
                <i class="${responseData.user_shortlisted_leads.includes(value.id) ? 'fas' : 'far'} fa-star"></i>
            </div>
          </td>
        </tr>`;

        count++;
    });


    $(displayDiv).html(html);
}

function populatePagination(paginationData) {
    $('#pagination').html(paginationData);
}
