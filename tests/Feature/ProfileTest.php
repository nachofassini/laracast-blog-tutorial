<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProfileTest extends TestCase
{
    use DatabaseMigrations;

    protected $user;

    protected function setUp()
    {
        parent::setUp();

        $this->user = create(\App\User::class);
    }

    /**
     * @test
     */
    public function aUserHasAProfile()
    {
        $this->get("/profiles/{$this->user->name}")
            ->assertSee($this->user->name);
    }

    /**
     * @test
     */
    public function aUserProfileMustShowTheirCreatedThreads()
    {
        $this->signIn($this->user);

        $userThread = create(\App\Thread::class, ['user_id' => $this->user->id]);
        $anotherUserThread = create(\App\Thread::class, []);

        $this->get("/profiles/{$this->user->name}")
            ->assertSee($this->user->name)
            ->assertSee($userThread->title)
            ->assertDontSee($anotherUserThread->title);
    }

    /**
     * @test
     */
    public function testGuestCanNotUploadAvatar()
    {
        $this->withExceptionHandling();

        $this->json('post', 'profile/avatar')
            ->assertStatus(401);
    }

    /**
     * @test
     */
    public function aUserCanUploadAValidAvatar()
    {
        $this->withExceptionHandling()->signIn();

        $this->json('post', 'profile/avatar', ['avatar' => 'not-an-image'])
            ->assertStatus(422);
    }

    /**
     * @test
     */
    public function aUserCanUploadAvatar()
    {
        Storage::fake('public');

        $this->signIn();

        $avatar = UploadedFile::fake()->image('avatar.jpg');

        $this->json('post', 'profile/avatar', ['avatar' => $avatar]);

        $this->assertEquals(asset("avatars/{$avatar->hashName()}"), auth()->user()->avatar);

        Storage::disk('public')->assertExists("avatars/{$avatar->hashName()}");
    }
}
