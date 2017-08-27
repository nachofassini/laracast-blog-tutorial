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

        factory(\App\Thread::class, 20)->create()->each(function($thread) {
            $thread->replies()->saveMany(factory(\App\Reply::class, rand(10, 15))->create());
        });
    }
}
