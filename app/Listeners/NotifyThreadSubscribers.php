<?php

namespace App\Listeners;

use App\Events\ThreadHasNewReply;
use App\Notifications\ThreadWasUpdated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyThreadSubscribers
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ThreadHasNewReply $event
     * @return void
     */
    public function handle(ThreadHasNewReply $event)
    {
        $event->thread->subscriptions()
            ->where('user_id', '!=', $event->reply->user_id)
            ->get()
            ->each->notify(new ThreadWasUpdated($event->thread, $event->reply));
    }
}
