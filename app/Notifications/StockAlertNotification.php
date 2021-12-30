<?php

namespace App\Notifications;

use App\Channels\SmsChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class StockAlertNotification extends Notification
{
    use Queueable;

    protected $productTitle;
    protected $message;
    protected $stockLevel;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($productTitle, $message, $stockLevel)
    {
        $this->productTitle = $productTitle;
        $this->message = $message;
        $this->stockLevel = $stockLevel;
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
                    ->line('Your stock levels for the product: '.$this->productTitle)
                    ->line('are as following: '.round($this->stockLevel, 2).'%')
                    ->line('which is '.$this->message)
                    ->action('Please follow the link to update stock', route('khata.inventory.product.stock'))
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
        // $url = $this->getActionUrl($notifiable);

        return ('Hello ' . $notifiable->name . ' Your stock levels for the product: '.$this->productTitle . ' are as following: '.round($this->stockLevel, 2).'%' . ' which is '.$this->message. '. Please follow the link to update stock ' . route('khata.inventory.product.stock'));
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

        ];
    }
}
