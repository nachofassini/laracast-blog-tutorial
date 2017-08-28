<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ReplyTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function itHasAnOwner()
    {
        $reply = factory(\App\Reply::class)->create();

        $this->assertInstanceOf(\App\User::class, $reply->owner);
    }
}
