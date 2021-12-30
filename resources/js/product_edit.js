$(function() {

    $(document).on('change', '.category', function() {
        var level = (Number) ($(this).attr('data-level'));
        var searchLevel = level + 1;

        //Removing dropdowns for all the level below the one user changed against
        $('.category').each(function(key,element) {
            if( $(element).attr('data-level') > level ) {
                var elementLevel = $(element).attr('data-level');
                $(`#level_${elementLevel}_div`).remove();
            }
        });


        $.ajax({
            url: base_url + '/categories/get-categories?level=' + searchLevel + '&parent_cat_id=' + $(this).val(),
            method: 'get',
            dataType: 'json',
            success: function(response) {
                if(response.suggestions.length > 0) {
                    var selectHtml = '<option value="">Select Sub Category</option>';
                    $(response.suggestions).each(function(key, suggestion) {
                        selectHtml += `<option value="${suggestion.data.id}">${suggestion.value}</option>`;
                    });
                    var html = ` <div class='w-full md:w-full px-3 mb-6' id="level_${searchLevel}_div">
                                    <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'>Select Sub Category</label>
                                    <div class="flex-shrink w-full inline-block relative">
                                        <select class="block appearance-none text-gray-600 w-full bg-white border border-gray-400 shadow-inner px-4 py-2 pr-8 rounded category" data-level="${searchLevel}" name="category[${searchLevel}]" id="category_level_${searchLevel}">
                                            ${selectHtml}
                                        </select>
                                        <div class="pointer-events-none absolute top-0 mt-3  right-0 flex items-center px-2 text-gray-600">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                                        </div>
                                    </div>
                                </div>`;
                    $(html).insertAfter(`#level_${level}_div`);
                }
            }
        });
    });

    var breadCrumb = $("#product_categories").val();
    breadCrumb = breadCrumb.split(',');

    var index = 0;
    $(breadCrumb).each(function(key, category_id) {
        index = key + 1;
        //Level 1 categories drop down already exists, therefore setting its value and creating sub level drop down
        if(index == 1) {
            $(`#category_level_${index}`).val(category_id);
            createCategoryDropDown(index);
        } else { //Setting value for level 2 and below, and creating sub level drop down for one level below the current level
            setValue(key, category_id, breadCrumb[key-1]);
            if(index < breadCrumb.length ) {
                createCategoryDropDown(index);
            }
        }
    });


});


var bindOpenFunction = function bindOpenModal(element) {
    document.getElementById('product-image-modal').classList.remove('hidden');
    document.getElementById('orignal_image_id').value = this.dataset.image_id;
    console.log(this.dataset.main_image);
    if(this.dataset.main_image == "1") {
        document.getElementById('is_main').setAttribute('checked',true);
        document.getElementById('is_main').setAttribute('disabled',true);
    } else {
        document.getElementById('is_main').removeAttribute('checked');
        document.getElementById('is_main').removeAttribute('disabled');
    }
}

var editImageBtn = document.getElementsByClassName('change_product_pic');
for (var i = 0; i < editImageBtn.length; i++) {
    editImageBtn[i].addEventListener('click', bindOpenFunction, false);
}

var confirmBtn = document.getElementById('confirm-btn');
confirmBtn.onclick = function() {
    document.getElementById('product-image-form').submit();
}

var bindCloseFunction = function bindCloseModal(element) {
    document.getElementById('product-image-modal').classList.add('hidden');
    document.getElementById('add-product-images-modal').classList.add('hidden');
    document.getElementById('orignal_image_id').value = "";
}

var closeModalBtn = document.getElementsByClassName('close-modal');
for (var i = 0; i < closeModalBtn.length; i++) {
    closeModalBtn[i].addEventListener('click', bindCloseFunction, false);
}

var addMoreImagesBtn = document.getElementById('product-add-images');
addMoreImagesBtn.onclick = function() {
    document.getElementById('add-product-images-modal').classList.remove('hidden');
}

var confirmAddMoreImgBtn =  document.getElementById('confirm-add-btn');
confirmAddMoreImgBtn.onclick = function () {
    document.getElementById('add-product-image-form').submit();
}

var bindSetMainImage = function(element) {
    var imageId = this.dataset.image_id;
    var productId = this.dataset.product_id;
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            console.log(this.response);
            location.reload();
        }
      };
      xhttp.open("get", base_url + '/product/set_main_image/' + productId + "/" + imageId , true);
      xhttp.send();
}

var setMainImage = document.getElementsByClassName('set_main_image');
for (var i = 0; i < setMainImage.length; i++) {
    setMainImage[i].addEventListener('click', bindSetMainImage, false);
}


var productSpecDiv = document.getElementById('specifications_div');
var noOfSpecs = Number(productSpecDiv.getAttribute('data-spec-counter'));
var addProductDetailsBtn = document.getElementById('add_product_details');
var hiddenPDinput = "<input type='hidden' name='product_details_id[]'>";
var keyHtml = "<div class='w-full md:w-full px-3 mb-6'>" +
              "<label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='grid-text-1'>Product Detail Key</label>" +
              "<input class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500' name='product_details_key[]' id='product_details_key_1' type='text' required>" +
              "</div>";
var valueHtml = "<div class='w-full md:w-full px-3 mb-6'>" +
                "<label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2' for='grid-text-1'>Product Detail Value</label>"+
                "<input class='appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500' name='product_details_value[]' id='product_details_key_1' type='text' required>" +
                "</div>";
var removeSpecLink = "<span class='w-full md:w-full px-3 mb-6 text-blue-500 underline remove-product-details'>Remove Specification</span>";

addProductDetailsBtn.onclick = function(event) {

    var specificationDiv = document.createElement('div');
    noOfSpecs++;
    productSpecDiv.setAttribute('data-spec-counter',noOfSpecs)
    specificationDiv.setAttribute('id',"specification_div_"+noOfSpecs);
    specificationDiv.innerHTML = hiddenPDinput + keyHtml + valueHtml + removeSpecLink;
    productSpecDiv.insertBefore(specificationDiv,productSpecDiv.lastChild);
}

document.addEventListener('click', function (e) {
    if (hasClass(e.target, 'remove-product-details')) {
        e.target.parentNode.remove();
    }
}, false);

function hasClass(elem, className) {
    return elem.className.split(' ').indexOf(className) > -1;
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

// reset counter and append file to gallery when file is dropped
function dropHandler(ev) {
    ev.preventDefault();
    for (const file of ev.dataTransfer.files) {
        addFile(gallery, file);
        overlay.classList.remove("draggedover");
        counter = 0;
    }
}

// only react to actual files being dragged
function dragEnterHandler(e) {
    e.preventDefault();
    if (!hasFiles(e)) {
        return;
    }
    ++counter && overlay.classList.add("draggedover");
}

function dragLeaveHandler(e) {
    1 > --counter && overlay.classList.remove("draggedover");
}

function dragOverHandler(e) {
    if (hasFiles(e)) {
        e.preventDefault();
    }
}

// event delegation to caputre delete events
// fron the waste buckets in the file preview cards
gallery.onclick = ({ target }) => {
    if (target.classList.contains("delete")) {
        const ou = target.dataset.target;
        document.getElementById(ou).remove(ou);
        gallery.children.length === 1 && empty.classList.remove("hidden");
        delete FILES[ou];
    } else if(target.classList.contains('main-image')) {
        const ou = target.dataset.target;
        let mainImageIndex = index(document.getElementById(ou));
        document.getElementById(ou).getElementsByClassName('main-image')[0].value = mainImageIndex;
    }
};

// print all selected files
// document.getElementById("submit").onclick = () => {
//   alert(`Submitted Files:\n${JSON.stringify(FILES)}`);
//   console.log(FILES);
// };

// clear entire selection
// document.getElementById("cancel").onclick = () => {
//   while (gallery.children.length > 0) {
//     gallery.lastChild.remove();
//   }
//   FILES = {};
//   empty.classList.remove("hidden");
//   gallery.append(empty);
// };


function index(element){
    var sib = element.parentNode.childNodes;
    var n = 0;
    for (var i=0; i<sib.length; i++) {
        if (sib[i]==element) return n;
        if (sib[i].nodeType==1) n++;
    }
    return -1;
}

function createCategoryDropDown(level) {
    var nextLevel = Number(level) + 1;
    var html = ` <div class='w-full md:w-full px-3 mb-6' id="level_${nextLevel}_div">
                              <label class='block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2'>Select Sub Category</label>
                              <div class="flex-shrink w-full inline-block relative">
                                  <select class="block appearance-none text-gray-600 w-full bg-white border border-gray-400 shadow-inner px-4 py-2 pr-8 rounded category" data-level="${nextLevel}" name="category[${nextLevel}]" id="category_level_${nextLevel}">
                                  </select>
                                  <div class="pointer-events-none absolute top-0 mt-3  right-0 flex items-center px-2 text-gray-600">
                                      <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                                  </div>
                              </div>
                          </div>`;
    $(html).insertAfter(`#level_${level}_div`);
}

function setValue(level, value, parent_cat_id) {

    level = Number(level) + 1;
    $.ajax({
        url: base_url + '/categories/get-categories?level=' + level + '&parent_cat_id=' + parent_cat_id,
        method: 'get',
        dataType: 'json',
        success: function(response) {
          if(response.suggestions.length > 0) {
            var selectHtml = '<option value="">Select Sub Category</option>';
            $(response.suggestions).each(function(key, suggestion) {
              selectHtml += `<option value="${suggestion.data.id}">${suggestion.value}</option>`;
            });

            $(`#category_level_${level}`).html(selectHtml);
            $(`#category_level_${level}`).val(value);

          }
        }
      });
}

