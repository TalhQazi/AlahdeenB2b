window.$ = window.jQuery = require('jquery');
import Chart from 'chart.js/auto';

$(function () {

    createTrendingLineChart();
    createDivisionPieChart();
    createBuyingTrendsBarChart();
    createReqForQuoteTrendsBarChart('req_for_quote_trends', 'Request For Quote Trends');
    createReqForQuoteTrendsBarChart('quotation_trends', 'Quotation Trends');
});

function createTrendingLineChart() {

    let trendChart = document.getElementById('trending');

    if($(trendChart).length == 0) {
        return false;
    }

    var trends = JSON.parse($(trendChart).attr('data-trends'));
    trendChart = trendChart.getContext('2d');

    var trendingLabels = [];
    var trendingData = [];
    var trendingBuyData = {
        label: 'Buying',
        borderColor: 'rgb(75, 192, 192)',
        data: []
    };
    var trendingSellData = {
        label: 'Selling',
        borderColor: 'rgb(255, 192, 192)',
        data: []
    };

    $(trends.months).each(function(key, monthLabel) {
        trendingLabels.push(monthLabel);
        if(monthLabel in trends.buying) {
            trendingBuyData.data.push(trends.buying[monthLabel]);
        } else {
            trendingBuyData.data.push(0);
        }

        if(monthLabel in trends.selling) {
            trendingSellData.data.push(trends.selling[monthLabel]);
        } else {
            trendingSellData.data.push(0);
        }

    });

    trendingData.push(trendingBuyData);
    trendingData.push(trendingSellData);


    let trendingChart = new Chart(trendChart, {
        type: 'line',
        data: {
            labels: trendingLabels,
            datasets: trendingData
        },
        options: {
            responsive:true,
            maintainAspectRatio: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Your Buying/Selling Trends'
                }
            }
        }
    });

}

function createDivisionPieChart() {

    let divisonChart = document.getElementById('total_division');

    if($(divisonChart).length == 0) {
        return false;
    }

    var divisions = JSON.parse($(divisonChart).attr('data-division'));

    divisonChart = divisonChart.getContext('2d');

    var totalDivisionData = [{
        backgroundColor: ['rgb(75, 192, 192)', 'rgb(255, 192, 192)'],
        data: [divisions.buying.grand_total, divisions.selling.grand_total],
    }];


    let totalDivisionChart = new Chart(divisonChart, {
        type: 'pie',
        data: {
            labels: ['buying', 'selling'],
            datasets: totalDivisionData
        },
        options: {
            responsive:false,
            maintainAspectRatio: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Your Buying/Selling Division'
                }
            }
        }
    });

}


function createBuyingTrendsBarChart() {

    let trendChart = document.getElementById('buying_trends');

    if($(trendChart).length == 0) {
        return false;
    }

    var trends = JSON.parse($(trendChart).attr('data-buying-trends'));
    trendChart = trendChart.getContext('2d');

    var trendingLabels = [];
    var trendingData = [];

    var trendingObjData = {
        label: 'Buying',
        backgroundColor: 'rgb(75, 192, 192)',
        data: []
    };


    $(trends.months).each(function(key, monthLabel) {
        trendingLabels.push(monthLabel);
        if(monthLabel in trends.trends) {
            trendingObjData.data.push(trends.trends[monthLabel]);
        } else {
            trendingObjData.data.push(0);
        }

    });

    trendingData.push(trendingObjData);

    let trendingChart = new Chart(trendChart, {
        type: 'bar',
        data: {
            labels: trendingLabels,
            datasets: trendingData
        },
        options: {
            responsive:true,
            maintainAspectRatio: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Your Buying/Selling Trends'
                }
            }
        }
    });

}

function createReqForQuoteTrendsBarChart(id, chartTitle) {

    let trendChart = document.getElementById(id);

    if($(trendChart).length == 0) {
        return false;
    }

    var trends = JSON.parse($(trendChart).attr('data-trends'));
    trendChart = trendChart.getContext('2d');
    console.log(trends);
    var trendingLabels = [];
    var trendingData = [];

    var trendingReceivedData = {
        label: 'Received',
        backgroundColor: 'rgb(75, 192, 192)',
        data: []
    };

    var trendingSentData = {
        label: 'Sent',
        backgroundColor: 'rgb(255, 192, 192)',
        data: []
    };

    $(trends.months).each(function(key, monthLabel) {
        trendingLabels.push(monthLabel);
        if(monthLabel in trends.received) {
            trendingReceivedData.data.push(trends.received[monthLabel]);
        } else {
            trendingReceivedData.data.push(0);
        }

        if(monthLabel in trends.sent) {
            trendingSentData.data.push(trends.sent[monthLabel]);
        } else {
            trendingSentData.data.push(0);
        }

    });

    trendingData.push(trendingReceivedData);
    trendingData.push(trendingSentData);

    let trendingChart = new Chart(trendChart, {
        type: 'bar',
        data: {
            labels: trendingLabels,
            datasets: trendingData
        },
        options: {
            responsive:true,
            maintainAspectRatio: true,
            plugins: {
                title: {
                    display: true,
                    text: chartTitle
                }
            }
        }
    });

}

