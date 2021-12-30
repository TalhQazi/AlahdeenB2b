window.$ = window.jQuery = require('jquery');
require('jquery-validation');

$(function () {
    $('#account_type').on('change', function () {
        switch (Number(this.value)) {
            case 1:
                $('#corporate').hide();
                $('#corporate :input').prop("disabled", true);
                $('#individual').hide();
                $('#individual :input').prop("disabled", true);
                break;
            case 2:
                $('#corporate').show();
                $('#corporate :input').prop("disabled", false);
                $('#individual').hide();
                $('#individual :input').prop("disabled", true);
                break;
            case 3:
                $('#corporate').hide();
                $('#corporate :input').prop("disabled", true);
                $('#individual').show();
                $('#individual :input').prop("disabled", false);
                break;
            case 4:
              $('#corporate').hide();
              $('#corporate :input').prop("disabled", true);
              $('#individual').hide();
              $('#individual :input').prop("disabled", true);
              break;
        }
    });

    validateBecomeSellerForm('#become_seller_form');
});


function validateBecomeSellerForm(formObj) {
  $(formObj).validate({
      rules: {
          industry: {
              required: {
                  depends: function (element) {
                      return $('#account_type').val() == 2;
                  }
              },
              lettersonly: true,
          },
          address: {
              required: {
                  depends: function (element) {
                      return $('#account_type').val() == 3;
                  }
              },
          },
          job_freelance: {
              required: {
                  depends: function (element) {
                      return $('#account_type').val() == 3;
                  }
              },
          },
      },
      errorPlacement: function (error, element) {
          $(error).insertAfter(element);
      }
  });
}
