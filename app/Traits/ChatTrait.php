<?php

namespace App\Traits;

use App\Events\MessageReceivedEvent;
use App\Models\Challan;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Musonza\Chat\Facades\ChatFacade;
use Musonza\Chat\Models\Conversation;

trait ChatTrait {

    public static function createConversation(User $buyer, User $seller, $data = [])
    {
        return ChatFacade::createConversation([$buyer,$seller], $data)->makePrivate()->makeDirect();
    }

    /**
    * Function responsible for sending message against an conversation
    *
    * @param Integer $conversationId
    * @param \App\Models\User $from
    * @param String $message
    * @param String $type
    */
    public static function sendMessage($conversation, User $from, $message, $type = 'text')
    {
      $to = $conversation->getParticipants()->where('id', '<>', $from->id)->first();
      if(in_array($type, config('chat_notifications_types_allowed'))) {
        MessageReceivedEvent::dispatch($from, $to->id, $message);
      }
      ChatFacade::message($message)->type($type)->from($from)->to($conversation)->send();

    }

    public static function getConversation($conversationId)
    {
        return ChatFacade::conversations()->getById($conversationId);
    }

    public static function getParticipantsConversation(User $participantModel1, User $participantModel2)
    {
        return ChatFacade::conversations()->between($participantModel1, $participantModel2);
    }

    public static function getUserConversations(User $user, $limit = 0, $pageNo = 0)
    {
      $conversations = ChatFacade::conversations()->setPaginationParams(['sorting' => 'desc'])
                      ->setParticipant($user);
      if(!empty($limit)) {
        $conversations = $conversations->limit($limit);
      }

      if(!empty($pageNo)) {
        $conversations = $conversations->page($pageNo);
      }

      return $conversations->get();
    }

    public static function sendQuotation($conversation, $quotationPath, User $from)
    {
      $sentMessage[] = '<div class="bg-white p-2"><a href="' . url(Storage::url($quotationPath)) . '" target="_blank"><img class="attachment" src="' . asset('/img/docs.png') . '"/></a></div>';
      self::sendMessage($conversation, $from, $quotationPath, 'quotation');

      $sentMessage[] = $lastMessage = __('Please find the quotation');
      self::sendMessage($conversation, $from, $lastMessage, 'text');

      return [
        'sent_message' => $sentMessage,
        'last_message' => $lastMessage
      ];
    }

    public static function markAsReadAll($conversation, User $user)
    {
      return ChatFacade::conversation($conversation)->setParticipant($user)->readAll();
    }

}
