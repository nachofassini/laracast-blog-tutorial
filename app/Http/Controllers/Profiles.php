<?php

namespace App\Http\Controllers;

use App\User;

class Profiles extends Controller
{
    public function show(User $profileUser)
    {
        $threads = $profileUser->threads()->paginate();

        return view('profiles.show', compact('profileUser','threads'));
    }
}
