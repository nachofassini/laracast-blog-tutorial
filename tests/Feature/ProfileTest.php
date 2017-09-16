<?php

namespace Tests\Feature;

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
}
