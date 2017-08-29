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
     * @param \Faker\Generator $faker
     */
    public function testAnAuthenticatedUserCanParticipateInForumThreads()
    {
        $this->be($user = factory(\App\User::class)->create());

        $thread = factory(\App\Thread::class)->create();

        $reply = factory(\App\Reply::class)->make();
        $this->post("{$thread->path()}/replies", $reply->toArray());

        $this->get("{$thread->path()}")
            ->assertSee($reply->body);
    }
}
