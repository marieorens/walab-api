<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GotMessageTest implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The message data to broadcast.
     *
     * @var mixed
     */
    public $message;

    /**
     * Create a new event instance.
     *
     * @param mixed $message
     * @return void
     */
    public function __construct($message)
    {
        $this->message = $message;
        // $this->code = $code;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('test.'. $this->message->code);
    }

    /**
     * Get the broadcastable data.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return [
            // 'code' => $this->code,
            'message' => $this->message,
        ];
    }
}
