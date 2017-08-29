<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ParticipateInForumTest extends TestCase
{
    use DatabaseMigrations;

    public function testAnUnauthenticaedUserShouldNotReplyAThread()
    {
        $this->expectException(\Illuminate\Auth\AuthenticationException::class);

        $this->post("threads/1/replies", []);
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
