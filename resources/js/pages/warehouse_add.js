window.$ = window.jQuery = require('jquery');
require('jquery-validation');
require('devbridge-autocomplete');
require('../components/image_upload');
import mapboxgl from 'mapbox-gl'; // or "const mapboxgl = require('mapbox-gl');"

mapboxgl.accessToken = 'pk.eyJ1IjoiYWJkdWwtYWhhZC1taXJ6YSIsImEiOiJja2s4ZDZlZXAwbXRpMndwOG01dmdieGN1In0.JcJ6grGu3ulxuRHyFn3Xnw';
const map = new mapboxgl.Map({
    container: 'map',
    style: 'mapbox://styles/mapbox/streets-v11', // stylesheet location
    center: [$("#lng").val(), $("#lat").val()], // starting position [lng, lat] user city at the time of registartion
    zoom: 8 // starting zoom
});

map.addControl(new mapboxgl.NavigationControl());

map.addControl(
    new mapboxgl.GeolocateControl({
        positionOptions: {
            enableHighAccuracy: true
        },
        trackUserLocation: true
    })
);

var currentStep = 0;
var steps = $('.step-div');
var marker = '';

$(document).ready(function() {

    // TO MAKE THE MAP APPEAR YOU MUST
    // ADD YOUR ACCESS TOKEN FROM
    // https://account.mapbox.com

    addMarker(map, $("#lng").val(), $("#lat").val());

    $("#city").autocomplete({
        minChars: 3,
        serviceUrl: base_url + '/get_locations?loc_level=city',
        dataType: 'json',
        preserveInput: true,
        onSelect: function (suggestion) {
            $(this).val(suggestion.value);
            $("#city_id").val(suggestion.data.id);
            $("#lat").val(suggestion.data.lat);
            $("#lng").val(suggestion.data.lng);

            moveToNewLocation(suggestion.data.lng, suggestion.data.lat, map);

        }
    });

    $("#locality").autocomplete({
        minChars: 3,
        serviceUrl: base_url + '/get_locations?loc_level=locality&city_id=' + $("#city_id").val(),
        dataType: 'json',
        preserveInput: true,
        onSelect: function (suggestion) {
            $(this).val(suggestion.value);
            $("#locality_id").val(suggestion.data.id);
            $("#lat").val(suggestion.data.lat);
            $("#lng").val(suggestion.data.lng);

            moveToNewLocation(suggestion.data.lng, suggestion.data.lat, map);
        }
    });

        $("#next_step").click(function(e) {
            e.preventDefault();
            validateForm('#add_warehouse_form');

            if( validateCurrentForm() ) {
                if(currentStep == 0) {
                    $("#previous_step").removeClass('hidden');
                } else if (currentStep == (steps.length - 1)) {
                    $("#add_warehouse_form").submit();
                    return false;
                }

                hideTab(currentStep);
                currentStep++;
                showTab(currentStep);
            }
        });

        $("#previous_step").click(function(e) {
            e.preventDefault();

            hideTab(currentStep);
            currentStep--;
            showTab(currentStep);

            if(currentStep == 0) {
                $("#previous_step").addClass('hidden');
            }

        });

        $(".warehouse_image").change(function() {
            readURL(this);
        });

        $('.remove_image').click(function() {
           $(this).parents('.warehouse-img-div').remove();
        });

        $(".set_main_image").click(function() {
            if (this.checked) {
                var warehouseId = $(this).attr('data-warehouse_id');
                var imageId = $(this).attr('data-image_id');
                $.ajax({
                    url: base_url + 'warehouse/' + warehouseId + '/warehouse_image/' + imageId,
                    method: 'get',
                    dataType: 'json',
                    success: function(response) {
                        $('.set_main_image').removeAttr('checked');
                        $('.set_main_image').prop('disabled',false);
                        $(this).prop('disabled', true);
                    }
                });
            }
        });

});

function validateCurrentForm() {
    var formStep = $('.form-step').attr('id');
    if(formStep == "basic_info") {
        return $('#add_warehouse_form').valid();
    } else if(formId == "location_details") {
        return $('#add_warehouse_form').valid();
    }
}

function validateForm(formObj) {

    $(formObj).validate({
        rules: {
            property_type_id: {
                required: true,
                number: true,
            },
            area: {
                required: true,
                number: true,
            },
            price: {
                required: true,
                number: true,
            },
            locality: {
                required: true,
            }
        },
        messages: {
            property_type_id: {
                required: "Select warehouse property type",
                number: "Rent per month can only consist of numbers or be in decimal form"
            },
            area: {
                required: "Please enter area of warehouse",
                number: "Rent per month can only consist of numbers or be in decimal form"
            },
            price: {
                required: "Please enter rent per month",
                number: "Rent per month can only consist of numbers or be in decimal form"
            },
            locality: {
                required: "Please enter locality",
            }
        }
    });

}

function hideTab(n) {

    var currentDiv = $(steps[n]).attr('data-target');
    $(currentDiv).addClass('hidden');
    $(steps[n]).removeClass('active');
}

function showTab(n) {

    var targetDiv = $(steps[n]).attr('data-target');
    $(targetDiv).removeClass('hidden');
    $(steps[n]).addClass('active');

    $('.transition').removeClass('active-transition');
    $(steps[n]).prev('.transition').addClass('active-transition');

    if (n == (steps.length - 1)) {
        $("#next_step").text("Submit");
    } else {
        $("#next_step").text("Next");
    }
}

function moveToNewLocation(lng,lat, map) {
    var flyToObj = {
        center: [
            lng,
            lat,
        ],
        zoom: 15,
        essential: true // this animation is considered essential with respect to prefers-reduced-motion
    }

    map.flyTo(flyToObj);

    addMarker(map, lng, lat);
}

function addMarker(map, lng, lat) {
    if(marker != "") {
        marker.remove();
    }

    marker = new mapboxgl.Marker({
        draggable: true
    })
    .setLngLat([lng, lat])
    .addTo(map);

    marker.on('dragend', onDragEnd);
}

function onDragEnd() {
    var lngLat = marker.getLngLat();

    addMarker(map, lngLat.lng, lngLat.lat);

    $("#lng").val(lngLat.lng);
    $("#lat").val(lngLat.lat);
}

function readURL(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      var inputId = $(input).attr('id');
      var photoNo = inputId.replace('photo_','');

      reader.onload = function(e) {
        $('#' + inputId + '_preview').attr('src', e.target.result);
        if(!$('#' + inputId + '_preview').hasClass('h-40')) {
            $('#' + inputId + '_preview').addClass('h-40');
        }

        $('#warehouse_image_id_' + photoNo).remove();
      }

      reader.readAsDataURL(input.files[0]); // convert to base64 string
    }
}





