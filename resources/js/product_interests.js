window.$ = window.jQuery = require('jquery');
require('jquery-validation');
require('devbridge-autocomplete');


var addMoreDocumentsBtn = document.getElementById('add-documents-btn');
addMoreDocumentsBtn.onclick = function() {
    document.getElementById('add-product-interests-modal').classList.remove('hidden');
}

var bindCloseFunction = function bindCloseModal(element) {
    document.getElementById('add-product-interests-modal').classList.add('hidden');
    document.getElementById('delete-modal').classList.add('hidden');
}

var closeModalBtn = document.getElementsByClassName('close-modal');
for (var i = 0; i < closeModalBtn.length; i++) {
    closeModalBtn[i].addEventListener('click', bindCloseFunction, false);
}

var confirmAddMoreBtn =  document.getElementById('confirm-add-btn');
confirmAddMoreBtn.onclick = function () {
    if($('#add-product-interest-form').valid()) {
        document.getElementById('add-product-interest-form').submit();
    }
}

var bindDocDeleteFunction = function bindDeleteModal(element) {
    console.log(element);
    document.querySelector("#delete-modal #modal-title").innerHTML = this.getAttribute('title');
    document.querySelector("#delete-modal form").setAttribute('action',this.getAttribute('data-href'))
    // $('#delete-modal form').attr('action',$(this).getAttribute('href'));
    document.getElementById('delete-modal').classList.remove('hidden');
}

var deleteDocBtn = document.getElementsByClassName('delete-product-interest');
for (var i = 0; i < deleteDocBtn.length; i++) {
    deleteDocBtn[i].addEventListener('click', bindDocDeleteFunction, false);
}

$(document).ready(function() {
    $("#required_product").autocomplete({
        minChars: 3,
        serviceUrl: base_url + '/product/get_products',
        dataType: 'json',
        // appendTo: '#product_suggestions',
        // onSelect: function (suggestion) {
        //     alert('You selected: ' + suggestion.value + ', ' + suggestion.data);
        // }
    });

    validateProductInteresetForm('#add-product-interest-form');
});


function validateProductInteresetForm(formObj) {

    $(formObj).validate({
        rules: {
            required_product: {
                required: true,
            },
            quantity: {
                required: true,
                digits: true
            },
            quantity_unit: {
                required: true,
            },

        },
        messages: {
            required_product: {
                required: "Please select product of interest",
            },
            quantity: {
                required: "Please select quantity",
                digits: "Please provide quantity in numbers only"
            },
            quantity_unit: {
                required: "Please select no quantity unit",
            }
        },
        submitHandler: function(form) {
            alert();
            // do other things for a valid form
            form.submit();
        }
    });
}

