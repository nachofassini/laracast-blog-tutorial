<?php

namespace Tests\Unit;

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
}
