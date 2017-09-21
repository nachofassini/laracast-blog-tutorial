<?php

namespace App\Http\Controllers;

use App\Thread;

class ThreadsSubscriptions extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function subscribe(Thread $thread)
    {
        $thread->subscribe();

        return response()->json('subscribed!', 201);
    }

    public function unSubscribe(Thread $thread)
    {
        $thread->unSubscribe();

        return response()->json('unsubscribed!');
    }
}
