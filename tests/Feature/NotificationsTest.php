<?php

namespace Tests\Feature;

use App\Thread;
use App\User;
use Illuminate\Notifications\DatabaseNotification;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class NotificationsTest extends TestCase
{
    use DatabaseMigrations;

    protected $user;
    protected $thread;

    protected function setUp()
    {
        parent::setUp();

        $this->user = create(User::class);
        $this->thread = create(Thread::class);

        $this->signIn($this->user);
    }

    /**
     * @test
     */
    function aNotificationIsPreparedWhenASubscribedThreadReceivesANewReplyThatIsNotByTheCurrentUser()
    {
        $this->markTestIncomplete();
        $this->thread->subscribe();

        $this->assertCount(0, $this->user->notifications);

        $this->thread->addReply([
            'user_id' => $this->user->id,
            'body' => 'Some reply here.'
        ]);

        $this->assertCount(0, $this->user->fresh()->notifications);

        $this->thread->addReply([
            'user_id' => create(User::class),
            'body' => 'Some other reply here.'
        ]);

        $this->assertCount(1, $this->user->fresh()->notifications);
    }

    /**
     * @test
     */
    public function testAUserCanFetchTheirUnreadNotifications()
    {
        create(DatabaseNotification::class, ['notifiable_id' => $this->user->id]);

        $this->assertCount(
            1,
            $this->getJson("/profiles/{$this->user->name}/notifications")->json()
        );
    }

    /**
     * @test
     */
    public function testAUserCanMarkANotificationAsRead()
    {
        create(DatabaseNotification::class, ['notifiable_id' => $this->user->id]);

        $this->assertCount(1, $this->user->fresh()->unreadNotifications);

        $this->delete("/profiles/{$this->user->name}/notifications/{$this->user->unreadNotifications->first()->id}");

        $this->assertCount(0, $this->user->fresh()->unreadNotifications);
    }
}
