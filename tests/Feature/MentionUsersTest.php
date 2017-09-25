<?php

namespace Tests\Feature;

use App\Notifications\YouWhereMentioned;
use App\Reply;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MentionUsersTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function testMentionedUsersInAReplyAreNotified()
    {
        $this->signIn($user = create(User::class));

        $mentionedUser = create(User::class);

        $reply = make(Reply::class, [
            'body' => "Mentioning user @{$mentionedUser->name} Hi!",
        ]);

        $this->post(route('replies.store', [$reply->thread->channel->slug, $reply->thread->id]), $reply->toArray());

        $this->assertDatabaseHas(
            'notifications',
            [
                'notifiable_id' => $mentionedUser->id,
                'notifiable_type' => get_class($mentionedUser),
                'type' => YouWhereMentioned::class,
            ]
        );
    }

    /**
     * @test
     */
    public function testUsersMentionedByTheirSerfAreNotNotified()
    {
        $this->signIn($user = create(User::class));

        $reply = make(Reply::class, [
            'body' => "Mentioning mi self @{$user->name} Hi mi self!",
        ]);

        $this->post(route('replies.store', [$reply->thread->channel->slug, $reply->thread->id]), $reply->toArray());

        $this->assertDatabaseMissing(
            'notifications',
            [
                'notifiable_id' => $user->id,
                'notifiable_type' => get_class($user),
                'type' => YouWhereMentioned::class,
            ]
        );
    }
}
