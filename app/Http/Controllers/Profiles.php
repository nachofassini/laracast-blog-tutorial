<?php

namespace App\Http\Controllers;

use App\Activity;
use App\User;

class Profiles extends Controller
{
    public function show(User $profileUser)
    {
        $activities = Activity::feed($profileUser);

        return view('profiles.show', compact('profileUser', 'activities'));
    }
}
