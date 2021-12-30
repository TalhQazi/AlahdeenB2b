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

        if(clone.querySelector(".main-image") != null) {
            clone.querySelector(".main-image").dataset.target = objectURL;
        }


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
    } else if(target.classList.contains('main-image')) {
        const ou = target.dataset.target;
        let mainImageIndex = index(document.getElementById(ou));
        if(document.getElementById('images_info')) {
            mainImageIndex = mainImageIndex + document.getElementsByClassName('warehouse-img-div').length;
        }
        document.getElementById(ou).getElementsByClassName('main-image')[0].value = mainImageIndex;
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

