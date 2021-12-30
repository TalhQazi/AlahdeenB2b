const e = require('jquery-datetimepicker');
const { ajax } = window.$ = window.jQuery = require('jquery');
import 'pusher-js';

$(document).ready(function() {

  Pusher.logToConsole = true;

  var channel = window.Echo.private(`message.received.${user_id}`);

  // var pusher = Window.Pusher.subscribe(`message-received.104`);
  channel.listen('MessageReceivedEvent', function(data) {
    var messagesCount = Number($('.cntmsg').text());
    messagesCount += 1;
    $('.cntmsg').text(messagesCount);
    createMessageNotification(data);

    if($("#messages_list").length > 0) {
      createMsgSent(data.lastMessage, 'justify-start');
    }
  });

});

function createMessageNotification(data) {
  if($(`#conversation_notification_${data.sender.participation[0].conversation_id}`).length > 0) {
    $(`#conversation_notification_${data.sender.participation[0].conversation_id}`).remove();
  }

  var profileImg = data.sender.profile_photo_path != null ? base_url + '/storage/' + data.sender.profile_photo_path : data.sender.profile_photo_url;
  var html = `<a id="conversation_notification_${data.sender.participation[0].conversation_id}" class="flex flex-row items-center justify-start px-4 py-4 capitalize font-medium text-sm tracking-wide bg-white hover:bg-gray-200 transition-all duration-300 ease-in-out" href="${base_url+'/chat/messages/'+data.sender.participation[0].conversation_id}">

              <div class="w-10 h-10 rounded-full overflow-hidden mr-3 bg-gray-100 border border-gray-300">
                <img class="w-full h-full object-cover" src="${profileImg}">
              </div>

              <div class="flex-1 flex flex-rowbg-green-100">
                <div class="flex-1">
                  <h1 class="text-sm font-semibold">${data.sender.name}</h1>
                  <p class="text-xs text-gray-500">${data.lastMessage}</p>
                </div>
              </div>

          </a>
          <hr>`;
  $("#message_notifications").prepend(html);

}

function createMsgSent(message, justifyClass) {
  var html =  '<div class="flex ' + justifyClass + ' mb-4">';
      html += '<div class="mr-2 py-3 px-4 bg-blue-400 rounded-bl-3xl rounded-tl-3xl rounded-tr-xl text-white max-w-md break-words">';
          html += message;
      html += '</div>';
  html += '</div>';
  $('.messages').append(html);
}
