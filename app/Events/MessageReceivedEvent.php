<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageReceivedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $receiverId;
    public $sender;
    public $lastMessage;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $sender, $receiverId, $lastMessage)
    {

      $this->receiverId = $receiverId;
      $this->sender = $sender;
      $this->sender->business = $sender->business;
      $this->sender->participation = $sender->participation;
      $this->lastMessage = $lastMessage;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('message.received.'.$this->receiverId);
    }

    // public function broadcastWith()
    // {
    //     return [
    //         'message' => [
    //             'id' => $this->receiver->getKey(),
    //         ],
    //     ];
    // }
}
