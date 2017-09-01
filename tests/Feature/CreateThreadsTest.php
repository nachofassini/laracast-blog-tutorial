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
        $this->withExceptionHandling();

        $this->get('threads/create', [])
            ->assertRedirect(route('login'));

        $this->post('threads', [])
            ->assertRedirect(route('login'));
    }

    /**
     * @test
     */
    public function testAnAuthenticatedUserCanCreateThreads()
    {
        $thread = make(\App\Thread::class);

        $this->signIn()
            ->post('threads', $thread->toArray());

        $threadCreated = \App\Thread::first();

        $this->get($threadCreated->path())
            ->assertStatus(200)
            ->assertSee($threadCreated->title)
            ->assertSee($threadCreated->body);
    }

    public function testAThreadRequiresTitle()
    {
        $this->postThread(['title' => null])
            ->assertSessionHasErrors('title');
    }

    public function testAThreadRequiresBody()
    {
        $this->postThread(['body' => null])
            ->assertSessionHasErrors('body');
    }

    public function testAThreadRequiresValidChannel()
    {
        $this->postThread(['channel_id' => null])
            ->assertSessionHasErrors('channel_id');

        $this->postThread(['channel_id' => 'a'])
            ->assertSessionHasErrors('channel_id');
    }

    public function postThread($override = [])
    {
        $thread = make(\App\Thread::class, $override)->toArray();

        return $this->withExceptionHandling()
            ->signIn()
            ->post('threads', $thread);
    }
}
