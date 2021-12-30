<?php

namespace App\Notifications;

use App\Channels\SmsChannel;
use App\Models\Invitation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class UserInvited extends Notification implements ShouldQueue
{
    use Queueable;

    protected $invitation;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Invitation $invitation)
    {
        $this->invitation = $invitation;
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
                    ->greeting('Hello ' . $notifiable->name)
                    ->line('someone has invited you to Emandii')
                    ->action('Please follow the link to accept invite', $this->getActionUrl($notifiable))
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
        // password reset link
        $url = $this->getActionUrl($notifiable);

        return ('Hello ' . $notifiable->name . ' someone has invited you to Emandii. Please follow the link to accept invite' . $url);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        Log::info('client added notification');
        Log::info(json_encode($notifiable));
        return [
            'id' => $notifiable->id,
            'name' => $notifiable->name,
        ];
    }

    private function getActionUrl($notifiable)
    {
        return url(route('invite.accept', [
            'token' => $this->invitation->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));
    }
}
