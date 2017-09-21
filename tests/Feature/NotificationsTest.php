<?php

namespace Tests\Feature;

use App\Thread;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class NotificationsTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    function aNotificationIsPreparedWhenASubscribedThreadReceivesANewReplyThatIsNotByTheCurrentUser()
    {
        $this->signIn();

        $thread = create(Thread::class);
        $thread->subscribe();

        $this->assertCount(0, auth()->user()->notifications);

        $thread->addReply([
            'user_id' => auth()->id(),
            'body' => 'Some reply here.'
        ]);

        $this->assertCount(0, auth()->user()->fresh()->notifications);

        $thread->addReply([
            'user_id' => create(User::class),
            'body' => 'Some other reply here.'
        ]);

        $this->assertCount(1, auth()->user()->fresh()->notifications);
    }

    /**
     * @test
     */
    public function testAUserCanFetchTheirUnreadNotifications()
    {
        $this->signIn();

        $thread = create(Thread::class);

        $thread->subscribe();

        $thread->addReply([
            'user_id' => create(User::class),
            'body' => 'Some other reply here.'
        ]);

        $user = auth()->user();

        $response = $this->getJson("/profiles/{$user->name}/notifications")->json();

        $this->assertCount(1, $response);
    }

    /**
     * @test
     */
    public function testAUserCanMarkANotificationAsRead()
    {
        $this->signIn();

        $thread = create(Thread::class);

        $thread->subscribe();

        $thread->addReply([
            'user_id' => create(User::class),
            'body' => 'Some other reply here.'
        ]);

        $user = auth()->user();

        $this->assertCount(1, $user->fresh()->notifications);

        $notificationId = $user->unreadNotifications->first()->id;

        $this->delete("/profiles/{$user->name}/notifications/{$notificationId}");

        $this->assertCount(0, $user->fresh()->unreadNotifications);
    }
}
