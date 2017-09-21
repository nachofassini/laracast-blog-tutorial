<?php

namespace Tests\Feature;

use App\Thread;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class SubscriptionsTest extends TestCase
{
    use DatabaseMigrations;

    protected $thread;

    protected function setUp()
    {
        parent::setUp();

        $this->thread = create(Thread::class);
    }

    /**
     * @test
     */
    function aGuestCantSubscribeToAnything()
    {
        $this->withExceptionHandling();

        $this->post("threads/1/subscribe")
            ->assertRedirect(route('login'));
    }

    /**
     * @test
     */
    function anAuthenticatedUserCanSubscribeToThreads()
    {
        $this->signIn()
            ->post("threads/{$this->thread->id}/subscribe");

        $this->assertCount(1, $this->thread->subscriptions);
    }

    /**
     * @test
     */
    function anAuthenticatedUserMayOnlySubscribeAThreadOnce()
    {
        $this->signIn();

        try {
            $this->post("threads/{$this->thread->id}/subscribe");
            $this->post("threads/{$this->thread->id}/subscribe");
        } catch (QueryException $e) {
            $this->fail('Did not expect to insert the same record twice.');
        }

        $this->assertCount(1, $this->thread->subscriptions);
    }

    /**
     * @test
     */
    function anAuthenticatedUserCanUnsubscribeFromThreads()
    {
        $this->signIn();

        $this->thread->subscribe();

        $this->delete("threads/{$this->thread->id}/subscribe");

        $this->assertCount(0, $this->thread->subscriptions);
    }
}
