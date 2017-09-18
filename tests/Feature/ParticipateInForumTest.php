<?php

namespace Tests\Feature;

use App\Channel;
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
            ->post("{$thread->path()}/replies", $reply->toArray());

        $this->get("{$thread->path()}")
            ->assertSee($reply->body);
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
}
