<?php

namespace Tests\Unit;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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

    /**
     * @test
     */
    public function testAUserHasAnAvatar()
    {
        $user = create(User::class);

        $this->assertEquals(asset('images/default-user.png'), $user->avatar);

        $user->avatar = 'avatars/me.jpg';

        $this->assertEquals(asset('avatars/me.jpg'), $user->avatar);
    }
}
