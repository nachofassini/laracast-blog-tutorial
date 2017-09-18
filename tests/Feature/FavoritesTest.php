<?php

namespace Tests\Feature;

use App\Reply;
use App\Thread;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class FavoritesTest extends TestCase
{
    use DatabaseMigrations;

    protected $thread;
    protected $reply;

    protected function setUp()
    {
        parent::setUp();

        $this->thread = create(Thread::class);
        $this->reply = create(Reply::class, ['thread_id' => $this->thread->id]);
    }

    /**
     * @test
     */
    function aGuestCantFavoriteAnything()
    {
        $this->withExceptionHandling();

        $this->post("replies/1/favorites")
            ->assertRedirect(route('login'));
    }

    /**
     * @test
     */
    function anAuthenticatedUserCanFavoriteAnyReply()
    {
        $this->signIn()
            ->post("replies/{$this->reply->id}/favorites");

        $this->assertCount(1, $this->reply->favorites);
    }

    /**
     * @test
     */
    function anAuthenticatedUserMayOnlyFavoriteAReplyOnce()
    {
        $this->signIn();

        try {
            $this->post("replies/{$this->reply->id}/favorites");
            $this->post("replies/{$this->reply->id}/favorites");
        } catch (QueryException $e) {
            $this->fail('Did not expect to insert the same record twice.');
        }

        $this->assertCount(1, $this->reply->favorites);
    }

    /**
     * @test
     */
    function anAuthenticatedUserCanUnfavoriteAnyReply()
    {
        $this->signIn();

        $this->reply->favorite();

        $this->delete("replies/{$this->reply->id}/favorites");

        $this->assertCount(0, $this->reply->favorites);
    }
}
