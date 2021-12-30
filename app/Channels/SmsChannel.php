<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use Log;

class SmsChannel
{
    /**
     * Send the given notification
     *
     * @param mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toSms($notifiable);

        // TODO: send SMS message using SMS service provider
        Log::info('TODO: send SMS Text message here... Link: ' . $message);

        // TODO: dispatch it into SMS queue

        return "Message sending Response";
    }
}
