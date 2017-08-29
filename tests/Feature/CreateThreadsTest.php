<?php

namespace Tests\Feature;

use Illuminate\Auth\AuthenticationException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function testGuestCantCreateThreads()
    {
        $this->expectException(AuthenticationException::class);

        $this->post('threads', []);
    }

    /**
     * @test
     */
    public function testAnAuthenticatedUserCanCreateThreads()
    {
        $thread = make(\App\Thread::class);

        $this->signIn()
            ->post('threads', $thread->toArray());

        $this->get($thread->path())
            ->assertStatus(200)
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }
}
