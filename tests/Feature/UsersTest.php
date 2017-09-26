<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Auth\AuthenticationException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UsersTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function testUnauthorizedUserCantFetchUsers()
    {
        $this->expectException(AuthenticationException::class);
        $this->getJson('/users');
    }

    /**
     * @test
     */
    public function testAnAuthorizedUserCanFetchUsers()
    {
        $users = create(User::class, [], 5);

        $this->signIn($users[0]);

        $response = $this->getJson('/users')
            ->assertSee($users[0]->name)
            ->json();

        $this->assertCount(5, $response);
    }

    /**
     * @test
     */
    public function testAnAuthorizedUserCanFetchUsersByName()
    {
        $paul = create(User::class, ['name' => 'paul']);
        create(User::class, ['name' => 'jane']);

        $this->signIn($paul);

        $response = $this->getJson('/users?name=pa')
            ->assertSee($paul->name)
            ->json();

        $this->assertCount(1, $response);
    }
}
