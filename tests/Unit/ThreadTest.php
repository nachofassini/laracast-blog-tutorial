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
        $this->thread = factory(\App\Thread::class)->create();
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
    public function testAThreadHaveAnOwner()
    {
        $this->assertInstanceOf(\App\User::class, $this->thread->creator);
    }

    public function testItCanAddAReply()
    {
        $reply = factory(\App\Reply::class)->create();

        $this->thread->addReply([
            'body' => $reply->body,
            'user_id' => 1
        ]);

        $this->assertCount(1, $this->thread->replies);
    }
}
