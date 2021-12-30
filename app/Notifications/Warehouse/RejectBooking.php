<?php

namespace App\Notifications\Warehouse;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Channels\SmsChannel;

class RejectBooking extends Notification
{
    use Queueable;

    public $warehouseBooking, $reason;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($bookingDetails, $reason)
    {
        $this->warehouseBooking = $bookingDetails;
        $this->reason = $reason;
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
        // TODO: add remove any more details related to warehouse booking
        return (new MailMessage)
                    ->greeting(__('Hi ').$notifiable->name.',')
                    ->line(__('Your request for booking warehouse with the following details have been rejected due to the following reason: '. $this->reason))
                    ->line(__('Booking ID: ').$this->warehouseBooking->id)
                    ->line(__('Warehouse ID: ').$this->warehouseBooking->warehouse_id)
                    ->line(__('Item: ').$this->warehouseBooking->item)
                    ->line(__('Description: ').$this->warehouseBooking->description)
                    ->line(__('Start Time: ').$this->warehouseBooking->start_time)
                    ->line(__('End Time: ').$this->warehouseBooking->end_time)
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toSms($notifiable)
    {
        // TODO: Send whatever data needs to be send in sms warehouse booking
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
        // TODO: add remove any more details related to warehouse booking
        return [
            'id' => $this->warehouseBooking->id,
            'warehouse_id' => $this->warehouseBooking->warehouse_id,
            'booked_by' => $this->warehouseBooking->booked_by,
            'item' => $this->warehouseBooking->item,
            'start_time' => $this->warehouseBooking->start_time,
            'end_time' => $this->warehouseBooking->end_time,
            'description' => $this->warehouseBooking->description,
            'booking_status' => $this->warehouseBooking->booking_status,
            'quantity' => $this->warehouseBooking->quantity,
            'unit' => $this->warehouseBooking->unit,
            'type' => $this->warehouseBooking->type,
            'area' => $this->warehouseBooking->area,
            'goods_value' => $this->warehouseBooking->goods_value,
        ];
    }
}
