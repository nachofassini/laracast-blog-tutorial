<?php

namespace Tests\Unit;

use App\Activity;
use Illuminate\Support\Collection;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ActivityTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function testItRecordsActivityWhenAThreadIsCreated()
    {
        $this->signIn();

        $thread = create(\App\Thread::class,  ['user_id' => auth()->id()]);

        $this->assertDatabaseHas(
            'activities', [
                'type' => 'created_thread',
                'user_id' => auth()->id(),
                'subject_id' => $thread->id,
                'subject_type' => get_class($thread),
            ]
        );

        $activity = Activity::first();

        $this->assertEquals($thread->id, $activity->subject->id);
        $this->assertEquals(get_class($thread), get_class($activity->subject));
    }

    /**
     * @test
     */
    public function testItRecordsActivityWhenAReplyIsCreated()
    {
        $this->signIn();

        $reply = create(\App\Reply::class,  ['user_id' => auth()->id()]);

        $this->assertDatabaseHas(
            'activities', [
                'type' => 'created_reply',
                'user_id' => auth()->id(),
                'subject_id' => $reply->id,
                'subject_type' => get_class($reply),
            ]
        );

        $activity = Activity::where('subject_type', get_class($reply))->first();

        $this->assertEquals($reply->id, $activity->subject->id);
        $this->assertEquals(get_class($reply), get_class($activity->subject));
        $this->assertCount(2, Activity::all());
    }
}
