<?php

namespace Tests\Unit;

use Illuminate\Support\Collection;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ChannelTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function testAChannelHaveThreads()
    {
        $channel = create(\App\Channel::class);
        factory(\App\Thread::class, 4)->create(['channel_id' => $channel->id]);

        $this->assertInstanceOf(Collection::class, $channel->threads);
        $this->assertInstanceOf(\App\Thread::class, $channel->threads->first());
    }
}
