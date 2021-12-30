window.$ = window.jQuery = require('jquery');


$(document).on('click', '.is_subscribed', function() {
    var isSubscribed = this.checked;
    var notificationTypeId = $(this).attr('data-type-id');
    $.ajax({
      url: base_url + '/notification-subscriptions/subscribe/' + notificationTypeId,
      method: 'post',
      data: {
        'is_subscribed': isSubscribed ? 1 : 0,
        '_token': $('meta[name="csrf-token"]').attr('content'),
      },
      success: function(response) {
        if(response.alertClass == 'alert-success') {
          $("#success_message").text(response.message);
          $("#success_message").parent('div').removeClass('hidden');
        } else {
          $("#error_message").text(response.message);
          $("#error_message").parent('div').removeClass('hidden');

          isSubscribed ? $(`#is_subscribed${notificationTypeId}`).prop('checked', false) : $(`#is_subscribed_${notificationTypeId}`).prop('checked', true);
        }
      }
    });
  });
