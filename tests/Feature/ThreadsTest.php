<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function anUserCanListsThreadsTest()
    {
        $thread = factory(\App\Thread::class)->create();
        $response = $this->get('/threads');

        $response->assertStatus(200)
            ->assertSee($thread->title);
    }

    /**
     * @test
     */
    public function itCanSeeAThread()
    {
        $thread = factory(\App\Thread::class)->create();
        $response = $this->get('/threads/' . $thread->id);

        $response->assertStatus(200)
            ->assertSee($thread->title);
    }
}
