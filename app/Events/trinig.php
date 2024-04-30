<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class trinig implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $state;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($state)
    {

        $this->state=$state;
        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('channee');
    }
    public function broadcastAs()
    {
      return 'channee';
      
       }
}
