window.$ = window.jQuery = require('jquery');
require('jquery-validation');

$(document).ready(function() {

    $("#search_keywords").keyup(function() {
        var url = base_url + '/admin/subscriptions';
        ajaxSearch(url, true, ".search_results");
    });

    $(document).on('click', ".edit_payment_status", function (event) {
        var currentStatus = $(this).attr('data-status');

        $("#subscription_payment_form input").each(function(key,element) {
            if($(element).attr('name') != "_token" && $(element).attr('name') != "_method") {
                $(element).val('');
            }
        });

        $("#subscription_payment_form textarea").val('');
        $("#subscription_payment_form select option").removeAttr('selected');

        $("#status option:contains(" + currentStatus + ")").attr('selected', 'selected');
        $("#status").trigger('change');

        $("#subscription_payment_form").attr('action', $(this).attr('data-target-url'));
        $("#subscription_payment_form #amount").val($(this).attr('data-amount'));
        $("#subscription-payment-modal").removeClass('hidden');
    });

    $(".close-modal").click(function() {
        $(".modal").each(function(index,element){

            if(!$(element).hasClass('hidden')) {
                $(element).addClass('hidden')
            }
        });
    });

    $("#update_status_btn").click(function(event) {

        if($('#subscription_payment_form').valid()) {
            $(this).attr('disabled',true);
            $("#subscription_payment_form").submit();
        }
    });

    $("#status").change(function() {
        $("#amount_div").hasClass('hidden') == false ? $("#amount_div").addClass('hidden') : '';
        $("#cancelled_div").hasClass('hidden') == false ? $("#cancelled_div").addClass('hidden') : '';

        if($(this).val() == "paid" && $("#amount_div").hasClass('hidden')) {
            $("#amount_div label").text("Paid Amount");
            $("#amount_div").removeClass('hidden');
        } else if($(this).val() == "refunded" && $("#amount_div").hasClass('hidden')) {
            $("#amount_div label").text("Refunded Amount");
            $("#amount_div").removeClass('hidden');
            $("#cancelled_div label").text("Refunded and closed");
            $("#cancelled_div").removeClass('hidden');
        } else if($(this).val() == "cancelled" && $("#cancelled_div").hasClass('hidden')) {
            $("#cancelled_div label").text("Cancelled and closed");
            $("#cancelled_div").removeClass('hidden');
        }
    });

    validatePaymentForm("#subscription_payment_form");

    $('body').on('click', '#pagination a', function(e) {
        e.preventDefault();

        $('#load a').css('color', '#dfecf6');
        $('#load').append('<img style="position: absolute; left: 0; top: 0; z-index: 100000;" src="/images/loading.gif" />');

        var url = $(this).attr('href');
        ajaxSearch(url, false, ".search_results");
        window.history.pushState("", "", url);
    });

});


function validatePaymentForm(formObj) {
    $(formObj).validate({
        rules: {
            status: {
                required:true,
            },
            description: {
                required: true,
            },
            amount: {
                required: {
                    depends: function (element) {
                        return $('#status').val() == "paid" || $('#status').val() == "refunded";
                    }
                },
            }
        },
        messages: {
            status: {
                required:"Status is required",
            },
            description: {
                required: "Description is required",
            },
            amount: {
                required: "Amount paid or refunded is required"
            }
        }
    });
}

const fileTempl = document.getElementById("file-template"),
imageTempl = document.getElementById("image-template"),
empty = document.getElementById("empty");

// use to store pre selected files
let FILES = {};

// check if file is of type image and prepend the initialied
// template to the target element
function addFile(target, file) {
    const isImage = file.type.match("image.*"),
    objectURL = URL.createObjectURL(file);

    const clone = isImage
        ? imageTempl.content.cloneNode(true)
        : fileTempl.content.cloneNode(true);

    clone.querySelector("h1").textContent = file.name;
    clone.querySelector("li").id = objectURL;
    clone.querySelector(".delete").dataset.target = objectURL;
    clone.querySelector(".size").textContent =
    file.size > 1024
        ? file.size > 1048576
        ? Math.round(file.size / 1048576) + "mb"
        : Math.round(file.size / 1024) + "kb"
        : file.size + "b";

    isImage &&
        Object.assign(clone.querySelector("img"), {
        src: objectURL,
        alt: file.name
    });

    empty.classList.add("hidden");
    target.prepend(clone);

    FILES[objectURL] = file;
}

const gallery = document.getElementById("gallery"),
overlay = document.getElementById("image-overlay");

// click the hidden input of type file if the visible button is clicked
// and capture the selected files
const hidden = document.getElementById("hidden-input");
document.getElementById("button").onclick = () => hidden.click();
    hidden.onchange = (e) => {
    for (const file of e.target.files) {
    addFile(gallery, file);
    }
};

// use to check if a file is being dragged
const hasFiles = ({ dataTransfer: { types = [] } }) =>
    types.indexOf("Files") > -1;

    // use to drag dragenter and dragleave events.
    // this is to know if the outermost parent is dragged over
    // without issues due to drag events on its children
    let counter = 0;


// event delegation to caputre delete events
// fron the waste buckets in the file preview cards
gallery.onclick = ({ target }) => {
    if (target.classList.contains("delete")) {
        const ou = target.dataset.target;
        document.getElementById(ou).remove(ou);
        gallery.children.length === 1 && empty.classList.remove("hidden");
        delete FILES[ou];
    }
};


function index(element){
    var sib = element.parentNode.childNodes;
    var n = 0;
    for (var i=0; i<sib.length; i++) {
        if (sib[i]==element) return n;
        if (sib[i].nodeType==1) n++;
    }
    return -1;
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
    $(response.subscription_orders.data).each(function(key, subscription) {

        var targetUrl = base_url + '/admin/subscriptions/' + subscription.id;

        html += `<tr class="hover:bg-gray-100 border-b border-gray-200 py-10">
                    <td class="px-4 py-4">${subscription.id}</td>
                    <td class="px-4 py-4">${subscription.user.name}</td>
                    <td class="px-4 py-4">${subscription.plan.name}</td>
                    <td class="px-4 py-4">${subscription.total_amount}</td>
                    <td class="px-4 py-4">${subscription.payment_method.name}</td>
                    <td class="px-4 py-4">${subscription.status}</td>
                    <td class="px-4 py-4">${subscription.created_at}</td>

                    <td class="px-4 py-4">
                        <span title="Update Payment Status" class="mx-0.5 edit_payment_status" data-target-url="${targetUrl}" data-amount="${subscription.total_amount}" data-status="${subscription.status}">
                            <i class="fa fa-pencil mx-0.5"></i>
                        </span>
                    </td>
                </tr>`;
    });

    $(displayDiv).html(html);
}

function populatePagination(paginationData) {
    $('#pagination').html(paginationData);
}
