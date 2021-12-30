<?php

namespace App\Notifications\Warehouse;

use App\Channels\SmsChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingAgreementOwner extends Notification
{
    use Queueable;

    public $agreementDetails;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($agreementDetails)
    {
        $this->agreementDetails = $agreementDetails;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'mail', SmsChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        // TODO: add remove any more details related to warehouse booking agreement
        return (new MailMessage)
        ->greeting(__('Hi ').$notifiable->name.',')
        ->line(__('Following are the final details agreed upon for finalizing the booking of your warehouse: '))
        ->line(__('Booking ID: ').$this->agreementDetails->booking_id)
        ->line(__('Warehouse ID: ').$this->agreementDetails->booking->warehouse_id)
        ->line(__('Item: ').$this->agreementDetails->item)
        ->line(__('Description: ').$this->agreementDetails->description)
        ->line(__('Start Time: ').$this->agreementDetails->start_time)
        ->line(__('End Time: ').$this->agreementDetails->end_time)
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
        // TODO: add remove any more details related to warehouse booking agreement
        $url = url('');

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
            'id' => $this->agreementDetails->id,
            'warehouse_id' => $this->agreementDetails->booking->warehouse_id,
            'booked_by' => $this->agreementDetails->booking->booked_by,
            'item' => $this->agreementDetails->item,
            'start_time' => $this->agreementDetails->start_time,
            'end_time' => $this->agreementDetails->end_time,
            'description' => $this->agreementDetails->description,
            'quantity' => $this->agreementDetails->quantity,
            'unit' => $this->agreementDetails->unit,
            'type' => $this->agreementDetails->type,
            'area' => $this->agreementDetails->area,
            'goods_value' => $this->agreementDetails->goods_value,
            'price' => $this->agreementDetails->price,
            'owner_terms' => $this->agreementDetails->user_terms
        ];
    }
}
