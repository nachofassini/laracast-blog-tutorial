<?php

namespace App\Events;

use App\Reply;
use App\Thread;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ThreadHasNewReply
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $thread;
    public $reply;

    /**
     * Create a new event instance.
     * @param Thread $thread
     * @param Reply $reply
     */
    public function __construct(Thread $thread, Reply $reply)
    {
        $this->thread = $thread;
        $this->reply = $reply;
    }
}
