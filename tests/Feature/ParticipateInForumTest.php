<?php

namespace Tests\Feature;

use App\Channel;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ParticipateInForumTest extends TestCase
{
    use DatabaseMigrations;

    public function testAnUnauthenticaedUserShouldNotReplyAThread()
    {
        $this->withExceptionHandling()
            ->post("threads/some-channel/1/replies", []);
    }

    /**
     * @test
     */
    public function testAnAuthenticatedUserCanParticipateInForumThreads()
    {
        $thread = create(\App\Thread::class);

        $reply = make(\App\Reply::class);
        $this->signIn()->post("{$thread->path()}/replies", $reply->toArray());

        $this->get("{$thread->path()}")
            ->assertSee($reply->body);
    }
}
