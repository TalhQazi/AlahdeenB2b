const { type, now } = require('jquery');

window.$ = window.jQuery = require('jquery');
require('jquery-validation');
require('../../../components/popup_modal');
import 'jquery-datetimepicker';

$(function () {
    $("#q").keyup(function() {
        if($(this).val().length > 0 && $(this).val().length < 3) {
          return false;
        } else {
            var url = base_url + '/khata/inventory/stock';
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

    $('body').on('click', '#stock_results_pagination a', function(e) {
        e.preventDefault();

        $('#load a').css('color', '#dfecf6');
        $('#load').append('<img style="position: absolute; left: 0; top: 0; z-index: 100000;" src="/images/loading.gif" />');

        var url = $(this).attr('href');
        loadStockLevelReport(url);

      });


    $(document).on('click', '.add_stock', function() {
        $('#popup-modal-add_stock').removeClass('hidden');

        $("#existing_product").val($(this).attr('data-product-id'));

        $('#add_product_stock').validate({
            rules: {
                existing_product: {
                    required: true
                },
                quantity: {
                    required: true,
                    min: 1,
                    digits: true
                }

            },
            messages: {
                existing_product: {
                    required: "Please select product from the given list."
                }
            },
            quantity: {
                required: "Please enter quantity",
                min: "Quantity to be added should at least be 1",
                digits: "Only numbers allowed"
            }
        });
    });

    $('#popup-modal-add_stock #confirm-btn').on('click', function () {
        $("form#add_product_stock").submit();
    });

    var startingDate = new Date();
    startingDate.setMonth(startingDate.getMonth() - 2);

    var currentDate = new Date();
    currentDate.setDate(currentDate.getDate() + 1);
    currentDate.setMonth(currentDate.getMonth() + 1);


    $('#date_timepicker_start').datetimepicker({
        format:'Y-m-d',
        value: startingDate.getFullYear() + '-' + startingDate.getMonth() + '-' + startingDate.getDate(),
        onShow:function( ct ){
         this.setOptions({
          maxDate:$('#date_timepicker_end').val()?$('#date_timepicker_end').val():false
         })
        },
        timepicker:false
    });

    $('#date_timepicker_end').datetimepicker({
        format:'Y-m-d',
        value: currentDate.getFullYear() + '-' + currentDate.getMonth() + '-' + currentDate.getDate(),
        onShow:function( ct ){
         this.setOptions({
          minDate:$('#date_timepicker_start').val()?$('#date_timepicker_start').val():false
         })
        },
        timepicker:false
    });

    $('#stock_report_start').datetimepicker({
        format:'Y-m-d',
        value: startingDate.getFullYear() + '-' + startingDate.getMonth() + '-' + startingDate.getDate(),
        onShow:function( ct ){
         this.setOptions({
          maxDate:$('#stock_report_end').val() ? $('#stock_report_end').val() : false
         })
        },
        timepicker:false
    });

    $('#stock_report_end').datetimepicker({
        format:'Y-m-d',
        value: currentDate.getFullYear() + '-' + currentDate.getMonth() + '-' + currentDate.getDate(),
        onShow:function( ct ){
         this.setOptions({
          minDate:$('#stock_report_start').val() ? $('#stock_report_start').val() : false
         })
        },
        timepicker:false
    });

    $("#refresh_records_btn").click(function() {
        loadStatsAndRecords();
    });

    $("#refresh_stock_report_btn").click(function() {
        loadStockReports();
        loadStockLevelReport('');
    });

    loadStatsAndRecords();
    loadStockReports();
    loadStockLevelReport('');
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
            if(response.products.data.length > 0) {
                populateTable(response);
                populatePagination(response.paginator, "#paginator");
            } else {
                var html = `<tr class="hover:bg-gray-100 border-b border-gray-200 py-10">
                    <td colspan="5" class="pxi-4 py-4 text-center">
                        No Stock found in Inventory
                    </td>
                </tr>`;

                $(".search_results").html(html);
            }

        }
    });
}


function populateTable(response) {

    var html = '';
    $(response.products.data).each(function(key, product) {
        html +=  `<tr class="hover:bg-gray-100 border-b border-gray-200 py-10">`;
        if(product.total_units != null) {
            html += `<td class="px-4 py-4">${product.id}</td>
                    <td class="px-4 py-4">${product.title}</td>
                    <td class="px-4 py-4">${product.total_units}</td>
                    <td class="px-4 py-4">${product.avg_interval_sale}</td>
                    <td class="px-4 py-4">${product.purchase_production_interval + ' ' +  product.purchase_production_unit}</td>
                    <td class="px-4 py-4">${product.remaining_products}</td>
                    <td class="px-4 py-4 ${product.quantity_status_class}">${product.quantity_status}</td>
                    <td class="px-4 py-4 controls">
                        <span>
                            <button class="add_stock" type="button" title="Add Stock" data-product-id="${product.id}">
                                <i class="far fa-plus-circle fa-lg"></i>
                            </button>
                        </span>
                    </td>`;

        } else {
            html += `<td colspan="8" class="px-4 py-4 text-center">
                        Quantity info is missing for ${product.title}.
                        <a target="_blank" class="underline" href="${base_url + '/khata/inventory/product-pricing'}">Please add missing info</a>
                    </td>`;
        }
        html +=  `</tr>`;
    });

    $(".search_results").html(html);
}

function populatePagination(paginationData, displayDiv) {
    $(displayDiv).html(paginationData);
}

function loadStatsAndRecords() {
    var startDate = $('#date_timepicker_start').val() + ' ' + '00:00:00';
    var endDate = $('#date_timepicker_end').val() + ' ' + '00:00:00';

    $.ajax({
        url: base_url + '/khata/inventory/stock/sales-records?start_date=' + startDate + '&end_date=' + endDate,
        type: "get",
        dataType: "json",
        success: function(response) {

            populateQtySaleRecords(response);
            populateSaleValRecords(response);
            mostSoldProductsRecords(response);
            risingProductsRecords(response);
            underPerformingRecords(response);

        },
        error: function(xhr, textStatus, errorThrown) {
            console.log(xhr.responseText);
        }
    });
}

function populateQtySaleRecords(response) {
    var html = '';
    if(response.qty_sales_record.length > 0) {
        $(response.qty_sales_record).each(function(key, product) {
            html += `<tr class="hover:bg-gray-100 border-b border-gray-200 py-10">
                        <td class="px-4 py-4">${product.title}</td>
                        <td class="px-4 py-4">${product.total_sold_units}</td>
                    </tr>`;

        });

    } else {

    }

    $("#qty_sales_record").html(html);
}

function populateSaleValRecords(response) {
    var html = '';
    if(response.value_sales_record.length > 0) {
        $(response.value_sales_record).each(function(key, product) {
            var totalSales = Number(product.total_sales);
            totalSales = totalSales.toFixed(2) + ' PKR';
            html += `<tr class="hover:bg-gray-100 border-b border-gray-200 py-10">
                        <td class="px-4 py-4">${product.title}</td>
                        <td class="px-4 py-4">${totalSales}</td>
                    </tr>`;

        });

    } else {

    }

    $("#value_sales_record").html(html);
}

function mostSoldProductsRecords(response, displayTbody) {
    var html = '';
    if(response.most_sold_products_record.length > 0) {
        $(response.most_sold_products_record).each(function(key, product) {
            var totalSales = Number(product.total_sales);
            totalSales = totalSales.toFixed(2) + ' PKR';
            html += `<tr class="hover:bg-gray-100 border-b border-gray-200 py-10">
                        <td class="px-4 py-4">${product.title}</td>
                        <td class="px-4 py-4">${totalSales}</td>
                        <td class="px-4 py-4">${product.total_sold_units}</td>
                    </tr>`;

        });

    } else {

    }

    $("#most_sold_products_record").html(html);
}

function risingProductsRecords(response) {
    var html = '';
    if(response.rising_products_record.length > 0) {
        $(response.rising_products_record).each(function(key, product) {
            var totalSales = Number(product.total_sales);
            totalSales = totalSales.toFixed(2) + ' PKR';
            html += `<tr class="hover:bg-gray-100 border-b border-gray-200 py-10">
                        <td class="px-4 py-4">${product.title}</td>
                        <td class="px-4 py-4">${totalSales}</td>
                        <td class="px-4 py-4">${product.total_sold_units}</td>
                    </tr>`;

        });

    } else {

    }

    $("#rising_products_record").html(html);
}

function underPerformingRecords(response) {
    var html = '';
    if(response.under_performing.length > 0) {
        $(response.under_performing).each(function(key, product) {
            var totalSales = Number(product.total_sales);
            totalSales = totalSales.toFixed(2) + ' PKR';
            html += `<tr class="hover:bg-gray-100 border-b border-gray-200 py-10">
                        <td class="px-4 py-4">${product.title}</td>
                        <td class="px-4 py-4">${totalSales}</td>
                        <td class="px-4 py-4">${product.total_sold_units}</td>
                    </tr>`;

        });

    } else {

    }

    $("#under_performing_record").html(html);
}

function loadStockReports() {
    var startDate = $('#stock_report_start').val() + ' ' + '00:00:00';
    var endDate = $('#stock_report_end').val() + ' ' + '00:00:00';

    $.ajax({
        url: base_url + '/khata/inventory/stock/stock-reports?start_date=' + startDate + '&end_date=' + endDate,
        type: "get",
        dataType: "json",
        success: function(response) {
            populateHighestRevenueGenerators(response);
            populateLargestDealsDone(response);
        },
        error: function(xhr, textStatus, errorThrown) {
            console.log(xhr.responseText);
        }
    });
}

function populateHighestRevenueGenerators(response)
{
    $("#revenue_contribution_div table tbody").html('');
    $("#revenue_contribution_div table thead tr").html(`<th class="px-4 py-2 " style="background-color:#f8f8f8">Total Revenue Generated</th>`);
    if(response.most_revenue_generating.products) {
        var tHeadHtml = '';
        var tBodyHtml = `<tr class="hover:bg-gray-100 border-b border-gray-200 py-10">
                            <td class="px-4 py-4">${response.most_revenue_generating.total_revenue + ' PKR'}</td>`;
        $(response.most_revenue_generating.products.title).each(function(key, title) {

            tHeadHtml = `<th class="px-4 py-2" style="background-color:#f8f8f8">${title}</th>`;
            tBodyHtml += `<td class="px-4 py-4">${response.most_revenue_generating.products.contribution[key]}</td>`;

            $("#revenue_contribution_div table thead tr").append(tHeadHtml);
        });

        tBodyHtml += `</tr>`;

        $("#revenue_contribution_div table tbody").html(tBodyHtml);
        $("#revenue_contribution_div").removeClass('hidden');
    }
}

function loadStockLevelReport(url) {
    var startDate = $('#stock_report_start').val() + ' ' + '00:00:00';
    var endDate = $('#stock_report_end').val() + ' ' + '00:00:00';

    if(url != '') {
        url = url + '&start_date=' + startDate + '&end_date=' + endDate;
    } else {
        url = base_url + '/khata/inventory/stock/level-reports?start_date=' + startDate + '&end_date=' + endDate;
    }

    $.ajax({
        url: url,
        type: "get",
        dataType: "json",
        success: function(response) {
            populateStockLvlReport(response);
            populatePagination(response.paginator, "#stock_results_pagination");
        },
        error: function(xhr, textStatus, errorThrown) {
            console.log(xhr.responseText);
        }
    });
}


function populateStockLvlReport(response) {

    var html = '';
    $(response.products.data).each(function(key, product) {
        html +=  `<tr class="hover:bg-gray-100 border-b border-gray-200 py-10">`;
        if(product.total_units != null) {
            html += `<td class="px-4 py-4">${product.title}</td>
                    <td class="px-4 py-4">${product.total_units}</td>
                    <td class="px-4 py-4">${product.total_sold_units}</td>
                    <td class="px-4 py-4">${product.avg_interval_sale}</td>
                    <td class="px-4 py-4">${product.purchase_production_interval + ' ' +  product.purchase_production_unit}</td>
                    <td class="px-4 py-4">${product.total_sales}</td>`;

        } else {
            html += `<td colspan="8" class="px-4 py-4 text-center">
                        Quantity info is missing for ${product.title}.
                        <a target="_blank" class="underline" href="${base_url + '/khata/inventory/product-pricing'}">Please add missing info</a>
                    </td>`;
        }
        html +=  `</tr>`;
    });

    $(".stock_level_results").html(html);
}

function populateLargestDealsDone(response) {

    var html = '';
    if(response.largest_deals_done.length > 0) {

        $(response.largest_deals_done).each(function(key, deal) {
            html +=  `<tr class="hover:bg-gray-100 border-b border-gray-200 py-10">
                        <td class="px-4 py-4">${deal.company_name}</td>
                        <td class="px-4 py-4">${deal.title}</td>
                        <td class="px-4 py-4">${deal.quantity}</td>
                    </tr>`;
        });
    } else {
        html += `<tr class="hover:bg-gray-100 border-b border-gray-200 py-10">
                    <td class="px-4 py-4 text-center">No records exist</td>
                </tr>`;
    }

    $(".largest_deals_done").html(html);
}

