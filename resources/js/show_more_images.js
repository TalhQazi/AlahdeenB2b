if(document.getElementsByClassName('view-more-images').length > 0) {

    document.getElementsByClassName('view-more-images')[0].onclick = function(event) {
      if(document.getElementsByClassName('view-more-images')[0].classList.contains('more')) {

          var productImagesElements = document.getElementsByClassName('product-images');
          var i;
          for(i = 0; i < productImagesElements.length; i++) {
              productImagesElements[i].classList.remove('hidden');
          }
          document.getElementsByClassName('view-more-images')[0].innerHTML = 'Show Less <i class="fa fa-angle-up"></i>';
          document.getElementsByClassName('view-more-images')[0].classList.remove('more');
          document.getElementsByClassName('view-more-images')[0].classList.add('less');
      }  else {
        var productImagesElements = document.getElementsByClassName('product-images');
        var i;
        for(i = 1; i < productImagesElements.length; i++) {
            productImagesElements[i].classList.add('hidden');
        }
        document.getElementsByClassName('view-more-images')[0].innerHTML = 'Show More <i class="fa fa-angle-down"></i>';
        document.getElementsByClassName('view-more-images')[0].classList.remove('less');
        document.getElementsByClassName('view-more-images')[0].classList.add('more');
      }
    }
}
