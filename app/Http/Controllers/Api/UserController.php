<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Actions\Fortify\CreateNewUser;
use App\Models\User;
use App\Traits\ApiResponser;
use App\Traits\ChatTrait;
use Illuminate\Support\Facades\Auth;
use Musonza\Chat\Facades\ChatFacade;

class UserController extends Controller
{

    use ApiResponser, ChatTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::select('id', 'name', 'email', 'city_id', 'profile_photo_path', 'industry')->where('id', Auth::user()->id)->get();
        if(!empty($user) && !empty($user[0])) {

            return $this->success(
              [
                'user' => $user[0]
              ]
            );

        } else {
          return $this->error(
            __('404 not found'),
            404
          );
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $createNewUser = new CreateNewUser();
        return $this->success(
            [
                'user' => $createNewUser->create($request->all())
            ],
            __('You have registered successfully')
        );
    }

   public function chatNotifications()
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
        $messageNotifications['message_info'][$conversation->id]['last_message'] = $lastMessage->body;
        $messageNotifications['message_info'][$conversation->id]['participant']['name'] = $otherParticipant->name;
        $messageNotifications['message_info'][$conversation->id]['participant']['profile_photo_path'] =  $otherParticipant->profile_photo_path;
      }

      return $this->success(
        $messageNotifications
      );
   }
}
