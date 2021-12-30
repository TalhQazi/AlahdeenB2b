<?php

namespace App\Notifications;

use App\Channels\SmsChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ChatReminder extends Notification
{
    use Queueable;

    public $reminderDetails;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($reminderDetails)
    {
        $this->reminderDetails = $reminderDetails;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', SmsChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->greeting(__('Hi ').$notifiable->name.',')
            ->line(__('Reminder for call'))
            ->line(__('Date And Time: ').$this->reminderDetails->reminder_date_time)
            ->line('Thank you for using our application!');
    }

    /**
     * Get the sms representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toSms($notifiable)
    {
        $url = _('Hi ').$notifiable->name.', Reminder for call: '.$this->reminderDetails->reminder_date_time;
        return ($url);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
