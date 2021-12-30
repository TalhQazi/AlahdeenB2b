<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RecordStatsEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $data;
    public $statsType;
    public $modelType;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($data, $statsType, $modelType)
    {
        $this->data = $data;
        $this->statsType = $statsType;
        $this->modelType = $modelType;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
