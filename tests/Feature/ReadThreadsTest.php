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

        $this->thread = factory(\App\Thread::class)->create();
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
        $this->get('/threads/' . $this->thread->id)
            ->assertStatus(200)
            ->assertSee($this->thread->title);
    }

    /**
     * @test
     */
    public function aUserCanReadThreadsReplies()
    {
        $reply = factory(\App\Reply::class)->create(['thread_id' => $this->thread->id]);

        $this->get("threads/{$this->thread->id}")
            ->assertSee($reply->body);
    }
}
