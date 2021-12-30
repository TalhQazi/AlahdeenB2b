<?php

namespace App\View\Composers;

use App\Models\User;
use App\Traits\ChatTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Musonza\Chat\Facades\ChatFacade;

class TopNavBarComposer
{
    use ChatTrait;
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function compose(View $view)
    {
      $conversations = ChatTrait::getUserConversations(Auth::user());

      $messageNotifications = [];
      $messageNotifications['unread_count'] = 0;
      foreach ($conversations as $conversation) {
        $conversation = $conversation->conversation;

        $otherParticipant = $conversation->getParticipants()->where('id', '<>', Auth::user()->id)->first();
        $otherParticipant->city = $otherParticipant->city->city;
        $otherParticipant->business = $otherParticipant->business;
        $otherParticipant->profile_photo_path = 'https://ui-avatars.com/api/?name='.$otherParticipant->name.'&color=7F9CF5&background=EBF4FF';
        if(!empty($otherParticipant->profile_photo_path)) {
          $otherParticipant->profile_photo_path = $otherParticipant->profile_photo_path;
        }
        $lastMessage = $conversation->last_message()->whereIn('type', ['text', 'image', 'document', 'catalog', 'quotation', 'quotation-request'])->first();
        $messageNotifications['unread_count'] += ChatFacade::conversation($conversation)->setParticipant(Auth::user())->unreadCount();
        $messageNotifications['message_info'][$conversation->id]['last_message'] = !empty($lastMessage) ? $lastMessage->body : "";
        $messageNotifications['message_info'][$conversation->id]['participant']['name'] = $otherParticipant->name;
        $messageNotifications['message_info'][$conversation->id]['participant']['profile_photo_path'] =  $otherParticipant->profile_photo_path;
      }

      $view->with('message_notifications', $messageNotifications);
    }
}
