<?php

namespace Tests\Unit;

use App\Reply;
use App\User;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ReplyTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function itHasAnOwner()
    {
        $reply = create(\App\Reply::class);

        $this->assertInstanceOf(\App\User::class, $reply->owner);
    }

    /**
     * @test
     */
    public function itKnowsIfItHasBeenRecentlyCreated()
    {
        $reply = create(\App\Reply::class);

        $this->assertTrue($reply->wasJustPublished());
    }

    /**
     * @test
     */
    public function itKnowsIfItWasNotRecentlyCreated()
    {
        $reply = create(\App\Reply::class);
        $reply->update(['created_at' => Carbon::now()->subMinutes(1)]);

        $this->assertFalse($reply->fresh()->wasJustPublished());
    }

    /** @test */
    function itCanDetectAllMentionedUsersInTheBody()
    {
        $reply = new Reply([
            'body' => '@JaneDoe wants to talk to @JohnDoe'
        ]);

        $this->assertEquals(['JaneDoe', 'JohnDoe'], $reply->mentionedUsers());
    }

    /**
     * @test
     */
    public function itWrapsMentionedUserNamesInTheBodyWithinAnchorTags()
    {
        $user = create(User::class);

        $reply = create(\App\Reply::class, [
            'body' => "Given we salute @{$user->name} by his name.",
        ]);

        $this->assertEquals(
            "Given we salute <a href=\"/profiles/{$user->name}\">@{$user->name}</a> by his name.",
            $reply->body);
    }
}
