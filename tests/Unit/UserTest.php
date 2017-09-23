<?php

namespace Tests\Unit;

use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function aUserCanFetchTheirMostRecentReply()
    {
        $this->signIn();

        $lastReply = create(\App\Reply::class, ['user_id' => auth()->id()]);
        $firstReply = create(\App\Reply::class, ['user_id' => auth()->id()]);
        $firstReply->update(['created_at' => Carbon::now()->subHour()]);

        $this->assertEquals($lastReply->id, auth()->user()->lastReply->id);
    }
}
