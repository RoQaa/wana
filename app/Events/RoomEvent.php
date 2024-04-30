<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RoomEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $state;
    public $data;
    public $room_id;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct( $state,$data,$room_id)
    {
        $this->state=$state;
        $this->data=$data;
        $this->room_id=$room_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('Room'.$this->room_id);
    }
    public function broadcastAs()
  {
    return 'Room';
    
     }
}
