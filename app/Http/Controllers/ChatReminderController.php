<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ChatReminder;
use App\Traits\ChatTrait;
use Illuminate\Support\Facades\Validator;

class ChatReminderController extends Controller
{

    public function setReminder($conversationId, Request $request, ChatReminder $chatReminder)
    {
        $this->authorize('setReminder', ChatReminder::class);
        if($request->ajax()) {

            Validator::make(
                $request->input(),
                [
                    'reminder_date_time' => ['required', "date", "date_format:Y-m-d H:i", "after:".date('Y-m-d H:i')]
                ],
                [
                    'reminder_date_time.after' => __('Reminder date can not be set in past.')
                ]
            )->validate();

            $chatReminder->conversation_id = $conversationId;
            $message = $chatReminder->reminder_date_time = $request->reminder_date_time;
            $chatReminder->user_id = Auth::user()->id;
            $chatReminder->save();

            $conversation = ChatTrait::getConversation($conversationId);
            ChatTrait::sendMessage($conversation, Auth::user(), $message, 'reminder');

            $sentMessage = '<div class="bg-gray p-2"><span><i class="fa fa-clock"></i> | ' . __('Call Reminder Set For: ') . $request->reminder_date_time . '</span></div>';


            return response()->json(['sent_message' => $sentMessage]);

        } else {
            return redirect()->route('chat.messages');
        }
    }

    public function getReminders($conversationId, Request $request, ChatReminder $chatReminder)
    {
        $this->authorize('getReminders', ChatReminder::class);
        if($request->ajax()) {

            $chatReminders = $chatReminder::where('conversation_id', $conversationId)
                                ->where('user_id', Auth::user()->id)
                                ->where('is_active', 1)
                                ->get();

            return response()->json(['reminders' => $chatReminders]);

        } else {
            return redirect()->route('chat.messages');
        }
    }

    public function markReminderAsDone(ChatReminder $chatReminder, Request $request)
    {
        $this->authorize('markReminderAsDone',[$chatReminder]);
        if($request->ajax()) {

            $chatReminder->is_active = 0;

            if($chatReminder->save()) {

                return response()->json(1);
            } else {
                return response()->json(0);
            }

        } else {
            return redirect()->route('chat.messages');
        }
    }
}
