function readURL(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      var inputId = $(input).attr('id');

      reader.onload = function(e) {
        $('#' + inputId + '_preview').attr('src', e.target.result);
        if(!$('#' + inputId + '_preview').hasClass('h-40')) {
            $('#' + inputId + '_preview').addClass('h-40');
        }

      }

      reader.readAsDataURL(input.files[0]); // convert to base64 string
    }
}
