<?php

namespace Tests\Feature;

use App\Channel;
use App\Thread;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ParticipateInForumTest extends TestCase
{
    use DatabaseMigrations;

    public function testAnUnauthenticaedUserShouldNotReplyAThread()
    {
        $this->withExceptionHandling()
            ->post("threads/some-channel/1/replies", []);
    }

    /**
     * @test
     */
    public function testAnAuthenticatedUserCanParticipateInForumThreads()
    {
        $thread = create(\App\Thread::class);
        $reply = make(\App\Reply::class);

        $this->signIn()
            ->json('post', "{$thread->path()}/replies", $reply->toArray())
            ->assertJsonFragment([
                'body' => $reply->body,
            ]);

        $this->assertDatabaseHas(
            'replies',
            [
                'thread_id' => $thread->id,
                'body' => $reply->body,
            ]
        );
    }

    public function testAReplyRequiresABody()
    {
        $thread = create(\App\Thread::class);
        $reply = make(\App\Reply::class, ['body' => '']);

        $this->withExceptionHandling()
            ->signIn()
            ->post("{$thread->path()}/replies", $reply->toArray())
            ->assertSessionHasErrors('body');
    }

    public function testUnauthorizedUsersCantUpdateReplies()
    {
        $this->withExceptionHandling();

        $reply = create(\App\Reply::class);

        $updatedBody = 'You been changed, fool.';
        $this->patch(route('replies.update', $reply), ['body' => $updatedBody])
            ->assertRedirect(route('login'));

        $this->signIn();

        $this->delete(route('replies.destroy', $reply))
            ->assertSee('unauthorized');
    }

    public function testAuthorizedUsersCanUpdateReplies()
    {
        $this->signIn();

        $reply = create(\App\Reply::class, [
            'user_id' => auth()->id()
        ]);

        $updatedBody = 'You been changed, fool.';
        $this->patch(route('replies.update', $reply), ['body' => $updatedBody])
            ->assertStatus(200);

        $this->assertDatabaseHas('replies', [
            'id' => $reply->id,
            'body' => $updatedBody
        ]);
    }

    public function testUnauthorizedUsersCantDeleteReplies()
    {
        $this->withExceptionHandling();

        $reply = create(\App\Reply::class);

        $this->delete(route('replies.destroy', $reply))
            ->assertRedirect(route('login'));

        $this->signIn();

        $this->delete(route('replies.destroy', $reply))
            ->assertSee('unauthorized');
    }

    public function testAuthorizedUsersCanDeleteReplies()
    {
        $this->withExceptionHandling()->signIn();

        $reply = create(\App\Reply::class, [
            'user_id' => auth()->id()
        ]);

        $thread = $reply->thread;

        $this->delete(route('replies.destroy', $reply))
            ->assertStatus(302)
            ->assertRedirect($thread->path());

        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
    }

    public function testAdminUsersCanDeleteReplies()
    {
        $this->withExceptionHandling()->signIn($user = create(\App\User::class, ['name' => 'nachofassini']));

        $reply = create(\App\Reply::class, [
            'user_id' => auth()->id()
        ]);

        $thread = $reply->thread;

        $this->delete(route('replies.destroy', $reply))
            ->assertStatus(302)
            ->assertRedirect($thread->path());

        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
    }

    public function testAUserMayNotReplyMoreThanOncePerMinute()
    {
        $this->signIn();

        $thread = create(Thread::class);
        $reply = make(\App\Reply::class, [
            'user_id' => auth()->id()
        ]);

        $this->post("{$thread->path()}/replies", $reply->toArray())
            ->assertStatus(201);

        $this->withExceptionHandling();

        $this->post("{$thread->path()}/replies", $reply->toArray())
            ->assertStatus(429);
    }
}
