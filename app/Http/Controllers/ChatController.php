<?php

namespace App\Http\Controllers;

use App\Models\Catalog;
use App\Models\ChatLabel;
use App\Models\ChatReminder;
use App\Models\Label;
use App\Models\ProductBuyRequirement;
use App\Models\User;
use App\Traits\ChatTrait;
use App\Traits\PackageUsageTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator as Validator;
use Musonza\Chat\Facades\ChatFacade;


class ChatController extends Controller
{
    use ChatTrait, PackageUsageTrait;

    public function createConversation(ProductBuyRequirement $productBuyRequirement)
    {
      $seller = Auth::user();
      $subscription = $this->getSubscription($seller->activeSubscriptions(), 'leads');

      if ($subscription) {
        if ($this->consumeLead($subscription, 'leads')) {
          $buyer = User::find($productBuyRequirement->buyer_id);
          if ($buyer && $productBuyRequirement) {
            $conversation = ChatTrait::createConversation($seller, $buyer, $productBuyRequirement->toArray())->makePrivate()->makeDirect();
            $message = ChatTrait::sendMessage($conversation, $seller, 'Hi ' . $buyer->name);
            $message = ChatTrait::sendMessage(
                $conversation,
                Auth::user(),
                '<p> Quotation Details </p><p>Product</p><p>' . $productBuyRequirement->quotation->product . '</p>',
                'html'
            );

            return redirect()->route('chat.messages');
          }
        } else {
           //All subscription have the leads consumed
           Session::flash('message', __('Need to purchase new package, since the leads limit has exceeded'));
           Session::flash('alert-class', 'alert-error');
           return redirect()->route('subscription.home');
        }
      } else {

        Session::flash('message', __('Need to purchase one of the packages before you can contact the buyer'));
        Session::flash('alert-class', 'alert-error');
        return redirect()->route('subscription.home');
      }
    }

    public function getConversations(Request $request, $conversation_id = null)
    {
      $subscription = $this->getSubscription(Auth::user()->activeSubscriptions(), 'leads');

      $page = $request->query('page', 1);

      if(!empty($conversation_id)) {
        $conversation = ChatTrait::getConversation($conversation_id);
        if(empty($conversation)) {
          abort('404');
        }
        $conversations = ChatFacade::conversation($conversation)->getParticipation(Auth::user())->where('conversation_id', $conversation_id)->where('messageable_id', Auth::user()->id)->get();
        $totalParticipants = 1;
      } else {
        $conversations = ChatTrait::getUserConversations(Auth::user(), 10, $page);
        $totalParticipants = $conversations->total();
      }
      $data = $this->getParticipantsInfo($conversations, $request);
        if ($request->ajax()) {
            return response()->json($data['participants']);
        } else {
            if (!empty($data['participants'])) {
                return view('pages.chat.chat')->with([
                    'participants' => $data['participants'],
                    'messages' => $data['messages'],
                    'total_participants' => $totalParticipants,
                    'labels' => Label::getAllLabels(),
                    'chat_labels' => $data['chat_labels'],
                    'chat_reminders' => $data['chat_reminders'],
                    'catalogs' => Catalog::where('user_id', Auth::user()->id)->get(),
                    'quantity_units' => config('quantity_unit')
                ]);
            } else {
                return redirect()->route('dashboard');
            }
        }
    }


    public function getParticipantsInfo($conversations, Request $request)
    {
        $participants = [];
        $messages = [];
        $labels = [];
        $reminders = [];
        $count = 0;
        $users = [];
        $index = 0;

        $searchParam = $request->query('search', '');

        if(!empty($searchParam)) {
            $users = $this->getSearchedUsers($searchParam);
        }

        foreach ($conversations as $conversation) {

            $conversation = $conversation->conversation;
            $otherParticipant = $this->getOtherParticipantData($conversation, $users, $searchParam);

            if(!empty($otherParticipant)) {

                $participants[$index][$conversation->id]['participant'] = $otherParticipant;
                $participants[$index][$conversation->id]['unread_count'] = $conversation->messages()->whereNotIn('type', ['notes', 'reminder'])->get()->count();

                $lastMessage = $conversation->last_message()->whereIn('type', ['text', 'image', 'document', 'catalog', 'quotation', 'quotation-request'])->first();

                if ($lastMessage->type == "document" || $lastMessage->type == "image") {
                    $lastMessage->body = __('Attachment');
                } else if ($lastMessage->type == 'catalog') {
                    $lastMessage->body = __('Catalog');
                } else if($lastMessage->type == 'quotation') {
                    $lastMessage->body = __('Quotation');
                } else if ($lastMessage->type == "quotation-request") {
                    $lastMessage->body = __('Enquiry');
                }

                $participants[$index][$conversation->id]['last_message'] = $lastMessage;

                if ($count == 0 && !$request->ajax()) {
                    ChatTrait::markAsReadAll($conversation, Auth::user());

                    $messages = $conversation->messages()->get();
                    $labels = ChatLabel::select('label_id')->where('conversation_id', $conversation->id)->where('user_id', Auth::user()->id)->get()->pluck('label_id')->toArray();
                    $reminders = ChatReminder::where('conversation_id', $conversation->id)->where('user_id', Auth::user()->id)->where('is_active', 1)->get();
                    $count++;
                }

                $index++;
            }

        }

        return array('participants' => $participants, 'messages' => $messages, 'chat_labels' => $labels, 'chat_reminders' => $reminders);
    }


    public function sendMessage($conversationId, Request $request)
    {
        if ($request->ajax()) {

            Validator::make(
                $request->all(),
                [
                    'message' => 'required_without:attachment_path|string|max:255',
                    'attachment_path' => 'required_without:message|file|mimes:pdf,jpeg,jpg,png|max:2048'
                ],
                [
                    'message.required_without' => __('Message is required in case no attachment is attached'),
                    'attachment_path.required_without' => __('Attachment is required in case no message is being sent')
                ],
                [
                    'attachment_path' => __('Attachment')
                ]
            )->validate();

            $sentMessage = [];
            $conversation = ChatTrait::getConversation($conversationId);
            $type = 'text';

            if (!empty($request->attachment_path)) {

                $attachment = $request->attachment_path;
                $message = $attachment->store('public/conversations/' . $conversationId . '/attachments');
                $fileType = $attachment->getClientOriginalExtension();

                if ($fileType != "jpg" && $fileType != "jpeg" && $fileType != "png") {
                    $type = 'document';
                    $sentMessage[] = '<div class="bg-white p-2"><a href="' . url(Storage::url($message)) . '" target="_blank"><img class="attachment" src="' . asset('/img/docs.png') . '"/></a></div>';
                } else {
                    $type = 'image';
                    $sentMessage[] = '<div class="bg-white p-2"><a href="' . url(Storage::url($message)) . '" target="_blank"><img class="attachment" src="' . url(Storage::url($message)) . '"/></a></div>';
                }

                $message = ChatTrait::sendMessage($conversation, Auth::user(), $message, $type);


                if (!empty($request->message)) {
                    $type = 'text';
                    $message = $sentMessage[] = $lastMessage = $request->message;
                    $message = ChatTrait::sendMessage($conversation, Auth::user(), $message, $type);
                } else {
                    $type = 'text';
                    $message = $sentMessage[] = $lastMessage = __('Please find the attachment');
                    $message = ChatTrait::sendMessage($conversation, Auth::user(), $message, $type);
                }
            } else {
                if (!empty($request->message)) {
                    $message = $sentMessage[] = $lastMessage = $request->message;
                    $message = ChatTrait::sendMessage($conversation, Auth::user(), $message, $type);
                } else {
                    return response()->json(['error' => __('Need to a attach file or atleast add a message')]);
                }
            }

            return response()->json(['sent_message' => $sentMessage, 'last_message' => $lastMessage]);

        }

    }

    public function getMessages($conversationId, Request $request)
    {
        if ($request->ajax()) {
            try {
                $data = [];
                $conversation = ChatFacade::conversations()->getById($conversationId);
                // ChatFacade::conversation($conversation)->setParticipant(Auth::user())->readAll();
                $messages = ChatFacade::conversation($conversation)->setParticipant(Auth::user())->getMessages();
                foreach ($messages as $key => $message) {
                    if ($message->type == "image" || $message->type == "document") {
                        $message->body = Storage::url($message->body);
                    }

                    $data[$key] = $message;
                }
                return response()->json($data);
            } catch (\Throwable $th) {
                return response()->json([$th->getMessage()], 400);
            }
        }
    }

    public function saveNotes($conversationId, Request $request)
    {
        if ($request->ajax()) {
            $conversation = ChatFacade::conversations()->getById($conversationId);

            try {
                if (!empty($request->notes)) {

                    $message = $request->notes;
                    $message = ChatFacade::message($message)->type('notes')->from(Auth::user())->to($conversation)->send();

                    $sentMessage = '<div class="bg-gray p-2"><span><i class="fa fa-sticky-note-o"></i> | ' . $request->notes . '</span></div>';
                } else {
                    // TODO handle else case
                }

                return response()->json(['sent_message' => $sentMessage]);
            } catch (\Throwable $th) {
                return response()->json([$th->getMessage()], 400);
            }
        }
    }

    public function getSearchedUsers($searchParam)
    {
        return User::with('city:id,city','business')
            ->where('name', 'like', '%'.$searchParam.'%')
            ->orWhere('phone', 'like', '%'.$searchParam.'%')
            ->orWhereHas('city', function($query) use ($searchParam) {
                $query->where('city', 'like', '%'.$searchParam.'%');
            })
            ->orWhereHas('business', function($query) use ($searchParam) {
                $query->where('company_name', 'like', '%'.$searchParam.'%')
                    ->orWhere('phone_number', 'like', '%'.$searchParam.'%');
            })
            ->get()->pluck('id')->toArray();
    }

    public function getOtherParticipantData($conversation, $users, $searchParam)
    {
        if(!empty($searchParam)) {
                $otherParticipant = $conversation->getParticipants()->where('id', '<>', Auth::user()->id)->first();

                if(!empty($users)) {
                    if(!in_array($otherParticipant->id, $users)) {
                        return [];
                    } else {
                        $otherParticipant->business = $otherParticipant->business;
                        $otherParticipant->city = $otherParticipant->city->city;
                        $otherParticipant->company_logo = !empty($otherParticipant->business)
                                                    && $otherParticipant->business->additionalDetails
                                                    ? Storage::url($otherParticipant->business->additionalDetails->logo) : '';
                        return $otherParticipant;
                    }
                } else {
                    return [];
                }
        } else {

            $otherParticipant = $conversation->getParticipants()->where('id', '<>', Auth::user()->id)->first();
            $otherParticipant->city = $otherParticipant->city->city;
            $otherParticipant->business = $otherParticipant->business;
            $otherParticipant->company_logo = !empty($otherParticipant->business)
                                                && $otherParticipant->business->additionalDetails
                                                ? Storage::url($otherParticipant->business->additionalDetails->logo) : '';

            return $otherParticipant;
        }

    }

    public function getConversationIds()
    {
      $conversations = ChatFacade::conversations()->setParticipant(Auth::user())->get()->pluck('id');
      return response()->json(
        $conversations
      );
    }
}
