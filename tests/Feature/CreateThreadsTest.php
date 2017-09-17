<?php

namespace Tests\Feature;

use App\Activity;
use App\Favorite;
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

    public function testUnauthorizedUsersCannotDeleteThreads()
    {
        $this->withExceptionHandling();

        $thread = create(\App\Thread::class);

        $this->delete(route('threads.destroy', $thread))
            ->assertRedirect(route('login'));

        $this->signIn();

        $this->delete(route('threads.destroy', $thread))
            ->assertSee('unauthorized');
    }

    public function testAuthorizedUsersCanDeleteThread()
    {
        $this->signIn($user = create(\App\User::class, ['name' => 'nachofassini']));

        $thread = create(\App\Thread::class, ['user_id' => $user->id]);
        $reply = create(\App\Reply::class, ['thread_id' => $thread->id]);
        Favorite::create([
            'user_id' => auth()->id(),
            'favorited_id' => $thread->id,
            'favorited_type' => get_class($thread)
        ]);
        Favorite::create([
            'user_id' => auth()->id(),
            'favorited_id' => $reply->id,
            'favorited_type' => get_class($reply)
        ]);

        $this->delete(route('threads.destroy', $thread))
            ->assertRedirect(route('threads.index'));

        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);

        $this->assertEquals(0, Activity::all()->count());
    }
}
