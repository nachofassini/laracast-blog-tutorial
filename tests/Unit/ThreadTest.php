<?php

namespace Tests\Unit;

use Illuminate\Support\Collection;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ThreadTest extends TestCase
{
    use DatabaseMigrations;

    protected $thread;

    protected function setUp()
    {
        parent::setUp();
        $this->thread = create(\App\Thread::class);
    }

    /**
     * @test
     */
    public function testAThreadCanMakeAStringPath()
    {
        $expectedPath = url("/threads/{$this->thread->channel->slug}/{$this->thread->id}");
        $this->assertEquals($expectedPath, $this->thread->path());
    }

    /**
     * @test
     */
    public function testAThreadMayHaveReplies()
    {
        $this->assertInstanceOf(Collection::class, $this->thread->replies);
    }

    /**
     * @test
     */
    public function testAThreadBelongsToAChannel()
    {
        $this->assertInstanceOf(\App\Channel::class, $this->thread->channel);
    }

    /**
     * @test
     */
    public function testAThreadHaveAnOwner()
    {
        $this->assertInstanceOf(\App\User::class, $this->thread->creator);
    }

    public function testItCanAddAReply()
    {
        $reply = create(\App\Reply::class);

        $this->thread->addReply([
            'body' => $reply->body,
            'user_id' => 1
        ]);

        $this->assertCount(1, $this->thread->replies);
    }
}
