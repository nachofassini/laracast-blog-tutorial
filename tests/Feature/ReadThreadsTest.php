<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ReadThreadsTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp()
    {
        parent::setUp();

        $this->thread = create(\App\Thread::class);
    }

    /**
     * @test
     */
    public function anUserCanListsThreadsTest()
    {
        $this->get('/threads')
            ->assertStatus(200)
            ->assertSee($this->thread->title);
    }

    /**
     * @test
     */
    public function itCanSeeAThread()
    {
        $this->get($this->thread->path())
            ->assertStatus(200)
            ->assertSee($this->thread->title);
    }

    /**
     * @test
     */
    public function aUserCanReadThreadsReplies()
    {
        $reply = create(\App\Reply::class, ['thread_id' => $this->thread->id]);

        $this->get($this->thread->path())
            ->assertSee($reply->body);
    }

    /**
     * @test
     */
    public function testAUserCanFilterThreadsAcordingToATag()
    {
        $channel = create(\App\Channel::class);
        $threadInChannel = create(\App\Thread::class, ['channel_id' => $channel->id]);
        $threadNotInChannel = create(\App\Thread::class);

        $this->signIn()
            ->get("threads/{$channel->slug}")
            ->assertSee($threadInChannel->title)
            ->assertDontSee($threadNotInChannel->title);
    }

    /**
     * @test
     */
    public function aUserCanFilterThreadsByAnyUserName()
    {
        $this->signIn($user = create(\App\User::class, ['name' => 'JohnDoe']));

        $threadByJohnDoe = create(\App\Thread::class, ['user_id' =>$user->id]);
        $threadNotByJohnDoe = create(\App\Thread::class);

        $this->get('threads?by=JohnDoe')
            ->assertSee($threadByJohnDoe->title)
            ->assertDontSee($threadNotByJohnDoe->title);
    }
}
