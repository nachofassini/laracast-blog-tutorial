<?php

namespace App\Listeners;

use App\Events\ThreadHasNewReply;
use App\Notifications\YouWhereMentioned;
use App\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyMentionedUsers
{
    /**
     * Handle the event.
     *
     * @param  ThreadHasNewReply $event
     * @return void
     */
    public function handle(ThreadHasNewReply $event)
    {
        if (sizeof($names = $this->searchMentionedUsersInReply($event->reply->body))) {
            User::whereIn('name', $names)
                ->where('id', '!=', auth()->id())
                ->get()
                ->each->notify(new YouWhereMentioned($event->reply));
        }
    }

    protected function searchMentionedUsersInReply($body = '')
    {
        preg_match_all('/\@([\S\d\-\_]+)/', $body, $matches);

        if (!empty($matches[1])) {
            return $matches[1];
        }
        return [];
    }
}
