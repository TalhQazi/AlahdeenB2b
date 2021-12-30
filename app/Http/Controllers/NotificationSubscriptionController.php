<?php

namespace App\Http\Controllers;

use App\Models\NotificationSubscription;
use App\Models\NotificationType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationSubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subscribedNotificationsIds = [];
        $subscribedNotifications = Auth::user()->subscribedNotifications();
        if(!empty($subscribedNotifications->NotificationSubscription)) {
            $subscribedNotificationsIds = $subscribedNotifications->pluck('notification_type_id');
        }

        return view('pages.notifications-subscription.index')->with([
            'notification_types' => NotificationType::getAllActive(),
            'subscribed_notification_ids' => $subscribedNotificationsIds
        ]);
    }

    public function subscribeNotification($notificationTypeId, Request $request) {
        $needsToBeSubscribed = $request->is_subscribed;

        $data = [];
        if($needsToBeSubscribed) {
            $created = NotificationSubscription::create([
                'notification_type_id' => $notificationTypeId,
                'user_id' => Auth::user()->id
            ]);

            if($created) {
                $data['message'] = __('Notification has been subscribed successfully');
                $data['alertClass'] = 'alert-success';
            } else {
                $data['message'] = __('Unable to subscribe to notification');
                $data['alertClass'] = 'alert-error';
            }

        } else {
            $deleted = NotificationSubscription::where('notification_type_id', $notificationTypeId)->where('user_id', Auth::user()->id)->delete();
            if($deleted) {
                $data['message'] = __('Notification has been unsubscribed successfully');
                $data['alertClass'] = 'alert-success';
            } else {
                $data['message'] = __('Unable to unsubscribe notification');
                $data['alertClass'] = 'alert-error';
            }

        }

        return response()->json($data);

    }

}
