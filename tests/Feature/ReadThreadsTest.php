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

        $this->assertDatabaseHas(
            'replies',
            [
                'thread_id' => $this->thread->id,
                'body' => $reply->body,
            ]
        );
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

    /**
     * @test
     */
    public function shouldListThreadsByPopularity()
    {
        $threadWithThreeReplies = create(\App\Thread::class);
        $threadWithFiveReplies = create(\App\Thread::class);

        create(\App\Reply::class, ['thread_id' => $threadWithThreeReplies->id], 3);
        create(\App\Reply::class, ['thread_id' => $threadWithFiveReplies->id], 5);

        $response = $this->getJson('threads?popular=1')->json();

        $this->assertEquals([5, 3, 0], array_column($response['data'], 'replies_count'));
    }

    /**
     * @test
     */
    public function shouldListThreadsUnanswered()
    {
        $threadWithReply = create(\App\Thread::class);
        create(\App\Reply::class, ['thread_id' => $threadWithReply->id]);

        $response = $this->getJson('threads?unanswered=1')->json();

        $this->assertCount(1, $response['data']);
    }

    /**
     * @test
     */
    public function aUserCanRequestRepliesForAGivenThread()
    {
        $reply = create(\App\Reply::class, ['thread_id' => $this->thread->id]);

        $this->getJson($this->thread->path() . '/replies')
            ->assertJson([
                'data' => [
                    $reply->toArray(),
                ],
                'total' => 1
            ]);
    }
}
