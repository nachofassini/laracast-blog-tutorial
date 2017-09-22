<?php

namespace Tests\Unit;

use App\Reply;
use App\User;
use Tests\TestCase;
use App\Notifications\ThreadWasUpdated;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Notification;

class ThreadTest extends TestCase
{
    use DatabaseMigrations;

    protected $thread;

    protected function setUp()
    {
        parent::setUp();
        $this->thread = create(\App\Thread::class);
    }

    /**
     * @test
     */
    public function testAThreadCanMakeAStringPath()
    {
        $expectedPath = url("/threads/{$this->thread->channel->slug}/{$this->thread->id}");
        $this->assertEquals($expectedPath, $this->thread->path());
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
    public function testAThreadBelongsToAChannel()
    {
        $this->assertInstanceOf(\App\Channel::class, $this->thread->channel);
    }

    /**
     * @test
     */
    public function testAThreadHaveAnOwner()
    {
        $this->assertInstanceOf(\App\User::class, $this->thread->creator);
    }

    /**
     * @test
     */
    public function testItCanAddAReply()
    {
        $reply = create(\App\Reply::class);

        $this->thread->addReply([
            'body' => $reply->body,
            'user_id' => 1
        ]);

        $this->assertCount(1, $this->thread->replies);
    }

    /**
     * @test
     */
    public function testAThreadNotifiesAllRegisteredSubscribersWhenAReplyIsAdded()
    {
        Notification::fake();

        $otherUser = create(\App\User::class);

        $this->signIn()->thread->subscribe()->addReply([
            'body' => 'Some reply',
            'user_id' => $otherUser->id,
        ]);

        Notification::assertSentTo(
            auth()->user(), ThreadWasUpdated::class
        );

        Notification::assertNotSentTo(
            [$otherUser], ThreadWasUpdated::class
        );
    }

    /**
     * @test
     */
    public function testAThreadCanBeSubscribedTo()
    {
        $this->signIn($user = create(\App\User::class));

        $this->thread->subscribe($user->id);

        $this->assertCount(1, $user->fresh()->subscriptionsList);
        $this->assertDatabaseHas(
            'subscriptions',
            [
                'subscribed_id' => $this->thread->id,
                'subscribed_type' => get_class($this->thread),
                'user_id' => auth()->id(),
            ]
        );
    }

    /**
     * @test
     */
    public function testAThreadCanBeUnSubscribedFrom()
    {
        $this->signIn($user = create(\App\User::class));

        $this->thread->subscribe($user->id);

        $this->assertCount(1, $user->fresh()->subscriptionsList);

        $this->thread->unSubscribe($user->id);

        $this->assertCount(0, $user->fresh()->subscriptionsList);
    }
}
