<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        factory(\App\User::class)->create([
            'name' => 'nachofassini',
            'email' => 'nachofassini@gmail.com',
            'password' => bcrypt('1234'),
        ]);

        factory(\App\Thread::class, 10)->create()->each(function ($thread) {
            $thread->replies()->saveMany(factory(\App\Reply::class, rand(4, 8))->create());
        });
    }
}
